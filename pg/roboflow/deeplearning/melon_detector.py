from ultralytics import YOLO
import cv2
import os
import pandas as pd
import numpy as np
from pathlib import Path
import json
import sys

def get_next_run_folder(output_folder):
    os.makedirs(output_folder, exist_ok=True)
    existing_runs = [d for d in os.listdir(output_folder) 
                    if os.path.isdir(os.path.join(output_folder, d)) 
                    and d.startswith('detect_')]
    
    if not existing_runs:
        next_run = 1
    else:
        numbers = [int(run.split('_')[1]) for run in existing_runs]
        next_run = max(numbers) + 1
    
    run_folder = os.path.join(output_folder, f'detect_{next_run}')
    os.makedirs(run_folder)
    return run_folder

def process_melon_color(cropped_img):
    hsv = cv2.cvtColor(cropped_img, cv2.COLOR_BGR2HSV)
    
    lower_range1 = np.array([155, 160, 160])
    upper_range1 = np.array([255, 240, 255])
    
    lower_range2 = np.array([0, 160, 160])
    upper_range2 = np.array([30, 240, 255])
    
    mask1 = cv2.inRange(hsv, lower_range1, upper_range1)
    mask2 = cv2.inRange(hsv, lower_range2, upper_range2)
    
    mask = cv2.bitwise_or(mask1, mask2)
    melon_only = cv2.bitwise_and(cropped_img, cropped_img, mask=mask)
    
    non_zero_pixels = melon_only[mask > 0]
    if len(non_zero_pixels) > 0:
        avg_bgr = np.mean(non_zero_pixels, axis=0)
        avg_b, avg_g, avg_r = avg_bgr
    else:
        avg_b, avg_g, avg_r = 0, 0, 0
    
    return melon_only, mask, (avg_r, avg_g, avg_b)

def process_single_image(img_path, output_folder, model_path):
    run_folder = get_next_run_folder(output_folder)
    
    crops_folder = os.path.join(run_folder, 'crops')
    masks_folder = os.path.join(run_folder, 'masks')
    os.makedirs(crops_folder)
    os.makedirs(masks_folder)
    
    model = YOLO(model_path)
    all_detections = []
    
    original_img = cv2.imread(img_path)
    results = model(img_path)[0]
    boxes = results.boxes
    
    annotated_img = results.plot()
    output_img_path = os.path.join(run_folder, f'detected_{os.path.basename(img_path)}')
    cv2.imwrite(output_img_path, annotated_img)
    
    for idx, box in enumerate(boxes):
        confidence = float(box.conf[0])
        class_id = int(box.cls[0])
        class_name = results.names[class_id]
        
        if class_name == 'melon-ripe':
            x1, y1, x2, y2 = box.xyxy[0].tolist()
            x1, y1, x2, y2 = int(x1), int(y1), int(x2), int(y2)
            
            width = x2 - x1
            height = y2 - y1
            
            cropped_img = original_img[y1:y2, x1:x2]
            melon_only, mask, (avg_r, avg_g, avg_b) = process_melon_color(cropped_img)
            
            base_name = os.path.splitext(os.path.basename(img_path))[0]
            crop_filename = f'{base_name}_crop_{idx+1}{os.path.splitext(img_path)[1]}'
            mask_filename = f'{base_name}_mask_{idx+1}{os.path.splitext(img_path)[1]}'
            
            crop_path = os.path.join(crops_folder, crop_filename)
            mask_path = os.path.join(masks_folder, mask_filename)
            cv2.imwrite(crop_path, cropped_img)
            cv2.imwrite(mask_path, melon_only)
            
            detection = {
                'image_name': os.path.basename(img_path),
                'crop_name': crop_filename,
                'mask_name': mask_filename,
                'class': class_name,
                'confidence': confidence,
                'x1': x1,
                'y1': y1,
                'x2': x2,
                'y2': y2,
                'width': width,
                'height': height,
                'avg_r': round(avg_r, 2),
                'avg_g': round(avg_g, 2),
                'avg_b': round(avg_b, 2)
            }
            all_detections.append(detection)
    
    results_data = {
        'run_folder': run_folder,
        'detected_image': output_img_path,
        'detections': all_detections
    }
    
    if all_detections:
        df = pd.DataFrame(all_detections)
        csv_path = os.path.join(run_folder, 'detections.csv')
        df.to_csv(csv_path, index=False)
        results_data['csv_path'] = csv_path
    
    print(json.dumps(results_data))

if __name__ == "__main__":
    if len(sys.argv) != 4:
        print("Usage: python melon_detector.py <image_path> <output_folder> <model_path>")
        sys.exit(1)
        
    img_path = sys.argv[1]
    output_folder = sys.argv[2]
    model_path = sys.argv[3]
    
    process_single_image(img_path, output_folder, model_path)
