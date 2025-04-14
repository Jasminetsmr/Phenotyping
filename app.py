from flask import Flask, request, jsonify, send_from_directory
from ultralytics import YOLO
import cv2
import numpy as np
import os

# Inisialisasi aplikasi Flask
app = Flask(__name__)

# Membuat folder static jika belum ada
if not os.path.exists('static'):
    os.makedirs('static')

# Memuat model YOLO
model = YOLO("C://xampp//htdocs//ds_min//best (2).pt")  # Ganti dengan path model yang benar

@app.route('/predict', methods=['POST'])
def predict():
    if 'file' not in request.files:
        return jsonify({"error": "No file part"})
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({"error": "No selected file"})
    
    # Membaca gambar yang diupload
    img = np.fromstring(file.read(), np.uint8)
    img = cv2.imdecode(img, cv2.IMREAD_COLOR)

    # Menggunakan model untuk prediksi
    results = model(img)  # Memproses gambar dengan model YOLO

    # Menyimpan gambar hasil prediksi
    output_path = 'static/predicted_image.jpg'
    img_with_boxes = img.copy()  # Salin gambar untuk gambar dengan bounding boxes

    for result in results:
        boxes = result.boxes.xywh.cpu().numpy()  # Mendapatkan koordinat bounding box
        labels = result.names  # Menyimpan label objek yang terdeteksi
        for i, box in enumerate(boxes):
            x1, y1, w, h = box[0], box[1], box[2], box[3]
            # Menggambar bounding box dan label
            cv2.rectangle(img_with_boxes, (int(x1 - w / 2), int(y1 - h / 2)), 
                          (int(x1 + w / 2), int(y1 + h / 2)), (0, 255, 0), 2)
            cv2.putText(img_with_boxes, labels[int(result.boxes.cls[i].item())], 
                        (int(x1 - w / 2), int(y1 - h / 2) - 10), 
                        cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)

    # Menyimpan gambar hasil deteksi ke folder static
    cv2.imwrite(output_path, img_with_boxes)

    # Mengembalikan URL gambar dan hasil prediksi
    return jsonify({
        'image_url': '/static/predicted_image.jpg',
        'predictions': [ 
            {
                'label': labels[int(result.boxes.cls[i].item())],
                'confidence': result.boxes.conf[i].item(),
                'x': box[0].item(),
                'y': box[1].item(),
                'width': box[2].item(),
                'height': box[3].item(),
            }
            for result in results
            for i, box in enumerate(result.boxes.xywh.cpu().numpy())
        ]
    })

if __name__ == '__main__':
    app.run(debug=True)
