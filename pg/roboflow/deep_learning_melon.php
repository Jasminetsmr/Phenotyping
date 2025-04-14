<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

// Increase upload limits
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_execution_time', '300');
ini_set('memory_limit', '1024M');

// Include database configuration
require_once $_SERVER['DOCUMENT_ROOT'] . '/coba_website/config/config.php';


// Base paths
$baseDir = realpath(__DIR__ . '/../../../../');
$resultsDir = $baseDir . '/results';
$outputDir = $baseDir . '/output';

// Create results and output directories if they don't exist
if (!file_exists($resultsDir)) {
    mkdir($resultsDir, 0777, true);
}
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// Function to get next detection folder
function getNextDetectionFolder($outputDir) {
    $maxNumber = 0;
    $pattern = $outputDir . '/detect_*';
    
    foreach (glob($pattern) as $folder) {
        if (preg_match('/detect_(\d+)$/', $folder, $matches)) {
            $number = (int)$matches[1];
            $maxNumber = max($maxNumber, $number);
        }
    }
    
    return 'detect_' . ($maxNumber + 1);
}

// Process plant information form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_plant_info'])) {
    // Get form data
    $growthId = $_POST['growth_id'] ?? '';
    $plantLabel = $_POST['plant_label'] ?? '';
    $plantConditionId = $_POST['plant_condition_id'] ?? '';
    $inputDate = $_POST['input_date'] ?? date('Y-m-d');
    $imgLoc = $_POST['img_loc'] ?? '';
    
    // Validate form data
    $errors = [];
    if (empty($growthId)) {
        $errors[] = "Growth ID is required";
    }
    if (empty($plantLabel)) {
        $errors[] = "Plant label is required";
    }
    if (empty($plantConditionId)) {
        $errors[] = "Plant condition is required";
    }
    
    // If no errors, proceed with insertion
    if (empty($errors)) {
        try {
            // Prepare SQL statement
            $sql = "INSERT INTO plant_information (growth_id, plant_label, plant_condition_id, input_date, img_loc) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            
            // Ensure input date is in YYYY-MM-DD format
            $formattedDate = date('Y-m-d', strtotime($inputDate));
            
            // Bind parameters
            $stmt->bind_param("isiss", $growthId, $plantLabel, $plantConditionId, $formattedDate, $imgLoc);
            
            // Execute the statement
            if ($stmt->execute()) {
                $success_message = "Plant information saved successfully";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
            
            // Close statement
            $stmt->close();
        } catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    } else {
        $error_message = "Please correct the following errors: " . implode(", ", $errors);
    }
}

// Function to get growth information
function getGrowthInformation($connection) {
    $sql = "SELECT gi.growth_id, gi.cultivation_start_date, gi.cultivation_end_date, 
            gi.species, gi.cultivation_method, gi.number_of_crop, gh.greenhouse_name 
            FROM growth_information gi 
            INNER JOIN greenhouse gh ON gi.greenhouse_id = gh.greenhouse_id 
            ORDER BY gi.growth_id DESC";
    
    $result = $connection->query($sql);
    $growthInfoArray = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $growthInfoArray[] = $row;
        }
    }
    
    return $growthInfoArray;
}

// Function to get plant conditions
function getPlantConditions($connection) {
    $sql = "SELECT plant_condition_id, condition_detail FROM plant_condition ORDER BY plant_condition_id";
    $result = $connection->query($sql);
    $conditionsArray = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $conditionsArray[] = $row;
        }
    }
    
    return $conditionsArray;
}

// Get growth information for dropdown
$growthInfoList = getGrowthInformation($connection);

// Get plant conditions for dropdown
$plantConditions = getPlantConditions($connection);

// Helper functions
function getColorForConfidence($confidence) {
    // Convert confidence to RGB
    if ($confidence >= 0.8) {
        // Green (80-100%)
        $ratio = ($confidence - 0.8) / 0.2; // Scale 0.8-1.0 to 0-1
        $r = 40 * (1 - $ratio);
        $g = 167;
        $b = 69;
    } elseif ($confidence >= 0.5) {
        // Yellow-Green to Yellow (50-80%)
        $ratio = ($confidence - 0.5) / 0.3; // Scale 0.5-0.8 to 0-1
        $r = 255 * (1 - $ratio);
        $g = 167 + (26 * (1 - $ratio));
        $b = 69 * (1 - $ratio);
    } else {
        // Red to Yellow-Red (0-50%)
        $ratio = $confidence / 0.5; // Scale 0-0.5 to 0-1
        $r = 220;
        $g = 53 + (120 * $ratio);
        $b = 69 * $ratio;
    }
    return sprintf('rgb(%d, %d, %d)', $r, $g, $b);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image'])) {
        $error = "No file was uploaded";
    } else {
        $uploadedFile = $_FILES['image'];
        
        // Check for upload errors
        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            switch ($uploadedFile['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $error = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error = "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error = "The uploaded file was only partially uploaded";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error = "No file was uploaded";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error = "Missing a temporary folder";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error = "Failed to write file to disk";
                    break;
                default:
                    $error = "Unknown upload error";
            }
        } else {
            $tempPath = $uploadedFile['tmp_name'];
            $originalName = $uploadedFile['name'];
            
            // Validate file size (max 50MB)
            $maxFileSize = 50 * 1024 * 1024; // 50MB in bytes
            if ($uploadedFile['size'] > $maxFileSize) {
                $error = "File is too large. Maximum size is 50MB";
            } 
            // Validate file type
            else {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $tempPath);
                finfo_close($finfo);
                
                if (!in_array($mimeType, $allowedTypes)) {
                    $error = "Invalid file type. Only JPG and PNG files are allowed";
                } else {
                    // Generate unique filename and create new detection folder
                    $timestamp = time();
                    $detectionFolder = $outputDir . '/' . getNextDetectionFolder($outputDir);
                    mkdir($detectionFolder, 0777, true);
                    
                    // Save raw image to detection folder
                    $rawImagePath = $detectionFolder . '/raw_' . $originalName;
                    if (move_uploaded_file($tempPath, $rawImagePath)) {
                        error_log("File uploaded successfully to: " . $rawImagePath);
                        
                        // Get selected model
                        $selectedModel = isset($_POST['model']) ? $_POST['model'] : 'chili_yolov8';
                        $pythonPath = 'C:/Users/Jasmine Tasmara/AppData/Local/Programs/Python/Python38/python.exe';
                        
                        // Map model selection to file path
                        switch($selectedModel) {
                            case 'ripeness':
                                $modelPath = $baseDir . '/app/content/Melon/deeplearning/ripeness.pt';
                                break;
                            case 'flower':
                                $modelPath = $baseDir . '/app/content/Melon/deeplearning/flower.pt';
                                break;
                            default:
                                $modelPath = $baseDir . '/app/content/Melon/deeplearning/chili_yolov8.pt';
                                break;
                        }

                        // Debug paths
                        error_log("Selected model: " . $selectedModel);
                        error_log("Model path: " . $modelPath . " (exists: " . (file_exists($modelPath) ? "yes" : "no") . ")");
                        error_log("Upload path: " . $rawImagePath . " (exists: " . (file_exists($rawImagePath) ? "yes" : "no") . ")");
                        error_log("Results dir: " . $resultsDir . " (exists: " . (file_exists($resultsDir) ? "yes" : "no") . ")");
                        
                        error_log("Starting detection process...");
                        
                        // Prepare the Python script with proper output flushing
                        $pythonScript = "
import os
import sys
import traceback
import json
from ultralytics import YOLO
import cv2
import numpy as np
import pandas as pd
from datetime import datetime

# Get timestamp from PHP
timestamp = '{$timestamp}'

# Force output buffering to be line-buffered
sys.stdout = os.fdopen(sys.stdout.fileno(), 'w', 1)
sys.stderr = os.fdopen(sys.stderr.fileno(), 'w', 1)

def save_image(img, path, description):
    if img is None:
        raise Exception(f'Cannot save {description} - image is None')
    
    success = cv2.imwrite(path, img)
    if not success:
        raise Exception(f'Failed to save {description} to: {path}')
    if not os.path.exists(path):
        raise Exception(f'File not found after saving {description}: {path}')
    
    print(f'Successfully saved {description} to: {path}', flush=True)
    return True

def process_melon_image(img, hsv_min, hsv_max):
    # Process melon image with HSV thresholding and calculate average RGB
    # Convert to HSV
    hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV)
    
    # Create mask for yellow-orange color
    lower_range1 = np.array([155, 160, 160])
    upper_range1 = np.array([255, 240, 255])
    
    lower_range2 = np.array([0, 160, 160])
    upper_range2 = np.array([30, 240, 255])
    
    mask1 = cv2.inRange(hsv, lower_range1, upper_range1)
    mask2 = cv2.inRange(hsv, lower_range2, upper_range2)
    
    mask = cv2.bitwise_or(mask1, mask2)
    result = cv2.bitwise_and(img, img, mask=mask)
    
    # Calculate average RGB for non-black pixels
    non_zero_pixels = result[mask > 0]
    if len(non_zero_pixels) > 0:
        avg_bgr = np.mean(non_zero_pixels, axis=0)
        avg_rgb = avg_bgr[::-1]  # Convert BGR to RGB
    else:
        avg_rgb = [0, 0, 0]
    
    return result, mask, avg_rgb

try:
    model_path = os.path.abspath(r'{$modelPath}')
    upload_path = os.path.abspath(r'{$rawImagePath}')
    results_dir = os.path.abspath(r'{$detectionFolder}')
    
    print(f'Current working directory: {os.getcwd()}', flush=True)
    print(f'Model path (absolute): {model_path}', flush=True)
    print(f'Upload path (absolute): {upload_path}', flush=True)
    print(f'Results directory (absolute): {results_dir}', flush=True)
    
    if not os.path.exists(model_path):
        raise Exception(f'Model file not found at: {model_path}')
    if not os.path.exists(upload_path):
        raise Exception(f'Upload file not found at: {upload_path}')
    if not os.path.exists(results_dir):
        os.makedirs(results_dir, exist_ok=True)
        print(f'Created results directory: {results_dir}', flush=True)
    
    # Define HSV ranges
    print('Using HSV ranges:', flush=True)
    print('Range 1: H=[155-255], S=[160-240], V=[160-255]', flush=True)
    print('Range 2: H=[0-30], S=[160-240], V=[160-255]', flush=True)
    
    # Initialize CSV data
    csv_data = []
    csv_columns = ['timestamp', 'detection_id', 'confidence', 'avg_r', 'avg_g', 'avg_b']
    
    print('Loading model...', flush=True)
    model = YOLO(model_path)
    print('Model loaded successfully', flush=True)
    
    print('Reading image...', flush=True)
    original_img = cv2.imread(upload_path)
    if original_img is None:
        raise Exception(f'Failed to read image at: {upload_path}')
    print(f'Image read successfully, shape: {original_img.shape}', flush=True)
    
    print('Running detection...', flush=True)
    results = model(original_img)
    if not results:
        raise Exception('No results returned from model')
    print('Detection completed successfully', flush=True)
    
    detection_results = []
    
    for r in results:
        boxes = r.boxes
        if len(boxes) == 0:
            print('No objects detected in this image', flush=True)
            continue
            
        print(f'Found {len(boxes)} objects', flush=True)
        img_height, img_width = original_img.shape[:2]
        
        for i in range(len(boxes)):
            try:
                box = boxes[i]
                x1, y1, x2, y2 = box.xyxy[0].cpu().numpy()
                conf = float(box.conf[0].cpu().numpy())
                cls = int(box.cls[0].cpu().numpy())
                cls_name = model.names[cls]
                
                print(f'Processing detection {i + 1}: {cls_name} ({conf:.2%})', flush=True)
                
                x1, y1, x2, y2 = map(int, [x1, y1, x2, y2])
                x1 = max(0, min(x1, img_width - 1))
                y1 = max(0, min(y1, img_height - 1))
                x2 = max(0, min(x2, img_width))
                y2 = max(0, min(y2, img_height))
                
                if x2 <= x1 or y2 <= y1:
                    print(f'Invalid bounding box: [{x1}:{x2}, {y1}:{y2}]', flush=True)
                    continue
                
                cropped_img = original_img[y1:y2, x1:x2].copy()
                if cropped_img.size == 0:
                    print('Cropped image is empty', flush=True)
                    continue
                
                crop_filename = f'{cls_name}_{i+1}_{int(conf*100)}pct_{timestamp}.jpg'
                crop_path = os.path.join(results_dir, crop_filename)
                
                save_image(cropped_img, crop_path, f'cropped {cls_name}')
                
                # Process melon-ripe images with HSV thresholding
                if cls_name == 'melon-ripe':
                    print(f'Processing melon-ripe image for color analysis...', flush=True)
                    
                    # Process the image
                    processed_img, mask, avg_rgb = process_melon_image(cropped_img, np.array([0, 160, 160]), np.array([30, 240, 255]))
                    
                    # Save processed images
                    processed_filename = f'{cls_name}_{i+1}_processed_{timestamp}.jpg'
                    mask_filename = f'{cls_name}_{i+1}_mask_{timestamp}.jpg'
                    processed_path = os.path.join(results_dir, processed_filename)
                    mask_path = os.path.join(results_dir, mask_filename)
                    
                    save_image(processed_img, processed_path, 'processed melon')
                    save_image(mask, mask_path, 'mask')
                    
                    # Add color data to CSV
                    csv_data.append({
                        'timestamp': timestamp,
                        'detection_id': i + 1,
                        'confidence': round(conf * 100, 2),
                        'avg_r': round(avg_rgb[0], 2),
                        'avg_g': round(avg_rgb[1], 2),
                        'avg_b': round(avg_rgb[2], 2)
                    })
                
                detection_results.append({
                    'class': cls_name,
                    'confidence': round(conf * 100, 2),
                    'bbox': {
                        'x1': x1,
                        'y1': y1,
                        'x2': x2,
                        'y2': y2,
                        'width': x2 - x1,
                        'height': y2 - y1
                    },
                    'cropped_image': crop_filename,
                    'processed_image': processed_filename if cls_name == 'melon-ripe' else None,
                    'mask_image': mask_filename if cls_name == 'melon-ripe' else None,
                    'color_analysis': {
                        'avg_r': round(avg_rgb[0], 2),
                        'avg_g': round(avg_rgb[1], 2),
                        'avg_b': round(avg_rgb[2], 2)
                    } if cls_name == 'melon-ripe' else None
                })
                
            except Exception as e:
                print(f'Error processing detection {i + 1}: {str(e)}', flush=True)
                traceback.print_exc(file=sys.stderr)
                continue
    
    # Save CSV file
    if csv_data:
        csv_path = os.path.join(results_dir, f'color_analysis_{timestamp}.csv')
        pd.DataFrame(csv_data).to_csv(csv_path, index=False)
        print(f'Saved color analysis to: {csv_path}', flush=True)
    
    # Save detection results
    json_path = os.path.join(results_dir, f'{timestamp}_detections.json')
    with open(json_path, 'w') as f:
        json.dump({
            'image_size': {'width': img_width, 'height': img_height},
            'detections': detection_results
        }, f, indent=2)
    print(f'Saved detection results to: {json_path}', flush=True)
    
    # Save visualization
    im_array = r.plot()
    output_path = os.path.join(results_dir, f'{timestamp}_result.jpg')
    save_image(im_array, output_path, 'result visualization')
    
    print('DETECTION_RESULTS_START', flush=True)
    json_output = json.dumps({
        'image_size': {'width': img_width, 'height': img_height},
        'detections': detection_results
    })
    print(json_output, flush=True)
    print('DETECTION_RESULTS_END', flush=True)

except Exception as e:
    print(f'Error: {str(e)}', file=sys.stderr, flush=True)
    traceback.print_exc(file=sys.stderr)
    sys.exit(1)
";
                        
                       // Perbaiki path model dengan raw string dan escape backslash
                       $modelPath = str_replace('\\', '\\\\', $modelPath); // Escape backslash
                       $rawImagePath = str_replace('\\', '\\\\', $rawImagePath); // Escape backslash
                       $detectionFolder = str_replace('\\', '\\\\', $detectionFolder); // Escape backslash
                       $pythonScript = str_replace('{$modelPath}', "r'{$modelPath}'", $pythonScript);
                       $pythonScript = str_replace('{$rawImagePath}', "r'{$rawImagePath}'", $pythonScript);
                       $pythonScript = str_replace('{$detectionFolder}', "r'{$detectionFolder}'", $pythonScript);
                       
                       // Write Python script to a temporary file
                       $scriptFile = tempnam(sys_get_temp_dir(), 'detect_') . '.py';
                       file_put_contents($scriptFile, str_replace(['{$timestamp}', '{$rawImagePath}', '{$detectionFolder}'], [$timestamp, $rawImagePath, $detectionFolder], $pythonScript));
                        
                        // Build and execute command
                        $cmd = "cd " . escapeshellarg($baseDir) . " && " . escapeshellarg($pythonPath) . ' ' . escapeshellarg($scriptFile) . " 2>&1";
                        error_log("Executing command: " . $cmd);
                        
                        exec($cmd, $output, $returnCode);
                        
                        // Clean up
                        unlink($scriptFile);
                        
                        error_log("Command output:\n" . implode("\n", $output));
                        error_log("Return code: " . $returnCode);
                        
                        if ($returnCode !== 0) {
                            $error = "Error processing image. Return code: " . $returnCode;
                            error_log($error);
                        } else {
                            $detectionFolderName = basename($detectionFolder);
                            // Gunakan path yang kompatibel dengan Windows
                            $baseUrl = "/coba_website/output/";
                            $resultImage = $baseUrl . $detectionFolderName . "/{$timestamp}_result.jpg";
                            $success = true;
                            
                            // Parse detection results
                            $detectionData = null;
                            $startFound = false;
                            $jsonData = '';
                            
                            foreach ($output as $line) {
                                error_log("Processing line: " . $line);
                                if (trim($line) === 'DETECTION_RESULTS_START') {
                                    $startFound = true;
                                    continue;
                                } elseif ($startFound && trim($line) === 'DETECTION_RESULTS_END') {
                                    break;
                                } elseif ($startFound) {
                                    $jsonData .= $line;
                                }
                            }
                            
                            if (!empty($jsonData)) {
                                error_log("Parsing JSON data: " . $jsonData);
                                $detectionData = json_decode($jsonData, true);
                                if ($detectionData === null) {
                                    error_log("JSON decode error: " . json_last_error_msg());
                                } else {
                                    error_log("Successfully parsed detection data");
                                }
                            } else {
                                error_log("No JSON data found in output");
                                error_log("Full output: " . print_r($output, true));
                            }
                        }
                    } else {
                        $error = "Error uploading file: " . error_get_last()['message'];
                        error_log("File upload error: " . error_get_last()['message']);
                    }
                }
            }
        }
    }
}
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Upload Card -->
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h4>Upload an Image for Detection</h4>
                            <p class="text-muted">Supported: JPG, PNG (Max 50MB)</p>
                        </div>

                        <form id="uploadForm" method="POST" enctype="multipart/form-data" class="dropzone-form">
                            <div class="form-group mb-4">
                                <label class="mb-2"><i class="fas fa-brain mr-2"></i>Select Detection Model</label>
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-primary active">
                                        <input type="radio" name="model" value="YOLOv8" checked> 
                                        <i class="fas fa-object-group mr-1"></i>YOLOv8
                                    </label>
                                    <label class="btn btn-outline-primary">
                                        <input type="radio" name="model" value="ripeness"> 
                                        <i class="fas fa-circle mr-1"></i>Ripeness
                                    </label>
                                    <label class="btn btn-outline-primary">
                                        <input type="radio" name="model" value="flower"> 
                                        <i class="fas fa-spa mr-1"></i>Flower
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image" accept="image/jpeg,image/png" required>
                                    <label class="custom-file-label" for="image">Choose file...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Select a clear image for better detection
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block" id="detectButton">
                                <i class="fas fa-search mr-2"></i>
                                Detect Objects
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Results Card -->
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title text-white">
                            <i class="fas fa-eye mr-2"></i>
                            Detection Results
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="loadingIndicator" style="display: none; margin: 200px auto 50px;">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <h5 class="mt-3">Processing image...</h5>
                            </div>
                        </div>
                        <div id="resultsContainer">
                            <?php if (isset($success)): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-image mr-2"></i>
                                                    Original Image
                                                </h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="<?php echo htmlspecialchars('/coba_website/output/' . $detectionFolderName . '/raw_' . $originalName); ?>" 
                                                     class="img-fluid rounded shadow" 
                                                     alt="Original Image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-object-group mr-2"></i>
                                                    Detection Result
                                                </h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="<?php echo htmlspecialchars($resultImage); ?>" 
                                                     class="img-fluid rounded shadow" 
                                                     alt="Detection Result">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($detectionData && isset($detectionData['detections'])): ?>
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                Detection Results (<?php echo count($detectionData['detections']); ?> objects found)
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Class</th>
                                                            <th>Confidence</th>
                                                            <!-- <th>Plant Condition</th> -->
                                                            <th>Position</th>
                                                            <th>Size</th>
                                                            <th>Details</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($detectionData['detections'] as $index => $detection): ?>
                                                            <tr class="detection-row">
                                                                <td><?php echo $index + 1; ?></td>
                                                                <td><?php echo htmlspecialchars($detection['class']); ?></td>
                                                                <td>
                                                                    <div class="progress" style="height: 25px; background: #f8f9fa;">
                                                                        <?php 
                                                                            $confidence_percent = $detection['confidence'];
                                                                            $bar_color = getColorForConfidence($confidence_percent / 100);
                                                                        ?>
                                                                        <div class="progress-bar" 
                                                                             role="progressbar" 
                                                                             style="width: <?php echo $confidence_percent; ?>%;
                                                                                    background-color: <?php echo $bar_color; ?>;
                                                                                    color: #fff;
                                                                                    font-weight: bold;
                                                                                    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);"
                                                                             aria-valuenow="<?php echo $confidence_percent; ?>" 
                                                                             aria-valuemin="0" 
                                                                             aria-valuemax="100">
                                                                            <?php echo number_format($confidence_percent, 2); ?>%
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <!-- <td>
                                                                    <?php 
                                                                    // Map class to plant condition
                                                                    $classToCondition = [
                                                                        'melon-ripe' => 'Berbuah (Matang)',
                                                                        'melon-unripe' => 'Berbuah (Belum Matang)',
                                                                        'melon-flower-bud' => 'Bunga (Kuncup)',
                                                                        'melon-flower-bloom' => 'Bunga (Bunga)',
                                                                        'melon-flower-wilt' => 'Bunga (Layu)',
                                                                        'melon-normal' => 'Normal',
                                                                        'melon-stunted' => 'Kerdil',
                                                                        'melon-wilt' => 'Layu',
                                                                        'melon-dead' => 'Mati'
                                                                    ];
                                                                    
                                                                    // Get condition ID from class name
                                                                    $conditionName = isset($classToCondition[$detection['class']]) ? 
                                                                        $classToCondition[$detection['class']] : 'Unknown';
                                                                    
                                                                    echo $conditionName;
                                                                    ?>
                                                                </td> -->
                                                                <td>
                                                                    x: <?php echo $detection['bbox']['x1']; ?>, 
                                                                    y: <?php echo $detection['bbox']['y1']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $detection['bbox']['width']; ?> x 
                                                                    <?php echo $detection['bbox']['height']; ?>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-primary" type="button" 
                                                                            data-toggle="collapse" 
                                                                            data-target="#detection<?php echo $index; ?>" 
                                                                            aria-expanded="false">
                                                                        <i class="fas fa-chevron-down"></i>
                                                                        View Details
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="6" class="p-0">
                                                                    <div class="collapse" id="detection<?php echo $index; ?>">
                                                                        <div class="card card-body border-0">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <h6>Original Cropped Image:</h6>
                                                                                    <img src="/coba_website/output/<?php echo basename($detectionFolder); ?>/<?php echo htmlspecialchars($detection['cropped_image']); ?>" 
                                                                                         class="img-fluid rounded" 
                                                                                         alt="Cropped <?php echo htmlspecialchars($detection['class']); ?>">
                                                                                </div>
                                                                                <?php if ($detection['class'] === 'melon-ripe'): ?>
                                                                                <div class="col-md-4">
                                                                                    <h6>Processed Image:</h6>
                                                                                    <img src="/coba_website/output/<?php echo basename($detectionFolder); ?>/<?php echo htmlspecialchars($detection['processed_image']); ?>" 
                                                                                         class="img-fluid rounded" 
                                                                                         alt="Processed <?php echo htmlspecialchars($detection['class']); ?>">
                                                                                    <h6 class="mt-3">HSV Mask:</h6>
                                                                                    <img src="/coba_website/output/<?php echo basename($detectionFolder); ?>/<?php echo htmlspecialchars($detection['mask_image']); ?>" 
                                                                                         class="img-fluid rounded" 
                                                                                         alt="Mask <?php echo htmlspecialchars($detection['class']); ?>">
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <div class="<?php echo $detection['class'] === 'melon-ripe' ? 'col-md-4' : 'col-md-8'; ?>">
                                                                                    <h6>Detailed Information:</h6>
                                                                                    <ul class="list-unstyled">
                                                                                        <li><strong>Class:</strong> <?php echo htmlspecialchars($detection['class']); ?></li>
                                                                                        <li><strong>Confidence:</strong> <?php echo $detection['confidence']; ?>%</li>
                                                                                        <li><strong>Position:</strong> (<?php echo $detection['bbox']['x1']; ?>, <?php echo $detection['bbox']['y1']; ?>)</li>
                                                                                        <li><strong>Size:</strong> <?php echo $detection['bbox']['width']; ?> x <?php echo $detection['bbox']['height']; ?></li>
                                                                                        <li><strong>Bounding Box:</strong></li>
                                                                                        <ul>
                                                                                            <li>Top-left: (<?php echo $detection['bbox']['x1']; ?>, <?php echo $detection['bbox']['y1']; ?>)</li>
                                                                                            <li>Bottom-right: (<?php echo $detection['bbox']['x2']; ?>, <?php echo $detection['bbox']['y2']; ?>)</li>
                                                                                        </ul>
                                                                                        <?php if ($detection['class'] === 'melon-ripe' && isset($detection['color_analysis'])): ?>
                                                                                        <li class="mt-3"><strong>Color Analysis:</strong></li>
                                                                                        <ul>
                                                                                            <li>Average RGB: 
                                                                                                <div class="d-inline-block" style="width: 20px; height: 20px; background-color: rgb(<?php 
                                                                                                    echo $detection['color_analysis']['avg_r']; ?>, <?php 
                                                                                                    echo $detection['color_analysis']['avg_g']; ?>, <?php 
                                                                                                    echo $detection['color_analysis']['avg_b']; ?>); border: 1px solid #ddd;"></div>
                                                                                            </li>
                                                                                            <li>R: <?php echo $detection['color_analysis']['avg_r']; ?></li>
                                                                                            <li>G: <?php echo $detection['color_analysis']['avg_g']; ?></li>
                                                                                            <li>B: <?php echo $detection['color_analysis']['avg_b']; ?></li>
                                                                                        </ul>
                                                                                        <?php endif; ?>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center p-5">
                                    <i class="fas fa-images fa-5x mb-3 text-muted"></i>
                                    <h4>No Image Processed Yet</h4>
                                    <p class="text-muted">Upload an image to see detection results</p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Debug Information Section -->
                            <div class="card mt-4">
                                <div class="card-header bg-secondary">
                                    <h5 class="card-title mb-0">
                                        <button class="btn btn-link text-white w-100 text-left p-0" type="button" 
                                                data-toggle="collapse" 
                                                data-target="#debugInfo" 
                                                aria-expanded="false">
                                            <i class="fas fa-bug mr-2"></i>
                                            Debug Information
                                            <i class="fas fa-chevron-down float-right"></i>
                                        </button>
                                    </h5>
                                </div>
                                <div class="collapse" id="debugInfo">
                                    <div class="card-body">
                                        <div class="debug-info" style="font-family: monospace; white-space: pre-wrap; font-size: 12px;">
                                            <h6>Latest Debug Log:</h6>
                                            <?php
                                            $debugLog = __DIR__ . '/debug.log';
                                            if (file_exists($debugLog)) {
                                                $logs = file_get_contents($debugLog);
                                                if ($logs) {
                                                    // Get last 50 lines
                                                    $lines = explode("\n", $logs);
                                                    $last50 = array_slice($lines, -50);
                                                    echo htmlspecialchars(implode("\n", $last50));
                                                } else {
                                                    echo "No debug logs found.";
                                                }
                                            } else {
                                                echo "Debug log file not found.";
                                            }
                                            ?>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <h6>Current Process Information:</h6>
                                            <ul class="list-unstyled">
                                                <?php if (isset($detectionFolder)): ?>
                                                <li><strong>Detection Folder:</strong> <?php echo htmlspecialchars($detectionFolder); ?></li>
                                                <?php endif; ?>
                                                <?php if (isset($modelPath)): ?>
                                                <li><strong>Model Path:</strong> <?php echo htmlspecialchars($modelPath); ?></li>
                                                <?php endif; ?>
                                                <?php if (isset($rawImagePath)): ?>
                                                <li><strong>Raw Image Path:</strong> <?php echo htmlspecialchars($rawImagePath); ?></li>
                                                <?php endif; ?>
                                                <?php if (isset($returnCode)): ?>
                                                <li><strong>Last Return Code:</strong> <?php echo htmlspecialchars($returnCode); ?></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        
                                        <?php if (isset($error)): ?>
                                        <div class="mt-4">
                                            <h6>Last Error:</h6>
                                            <div class="alert alert-danger">
                                                <?php echo nl2br(htmlspecialchars($error)); ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($output) && is_array($output)): ?>
                                        <div class="mt-4">
                                            <h6>Command Output:</h6>
                                            <div class="bg-light p-3 rounded">
                                                <?php echo nl2br(htmlspecialchars(implode("\n", $output))); ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
 
<!-- Plant Information Form -->
<section class="content mt-4" id="plantInfoSection" style="display: none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-success">
                    <div class="card-header bg-success">
                        <h3 class="card-title text-white">
                            <i class="fas fa-seedling mr-2"></i>
                            Plant Information
                        </h3>
                    </div> 
                    <div class="card-body">
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success alert-message">
                                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger alert-message">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form id="plantInfoForm" method="POST">
                            <div class="form-group">
                                <label for="growth_id">Growth ID:</label>
                                <select class="form-control" id="growth_id" name="growth_id">
                                    <?php foreach ($growthInfoList as $growthInfo): ?>
                                        <option value="<?php echo $growthInfo['growth_id']; ?>">
                                            <?php echo $growthInfo['growth_id']; ?> (<?php echo $growthInfo['species']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="plant_label">Plant Label:</label>
                                <input type="text" class="form-control" id="plant_label" name="plant_label" required>
                            </div>
                            <div class="form-group">
                                <label for="plant_condition_id">Plant Condition:</label>
                                <select class="form-control" id="plant_condition_id" name="plant_condition_id">
                                    <?php foreach ($plantConditions as $condition): ?>
                                        <option value="<?php echo $condition['plant_condition_id']; ?>">
                                            <?php echo $condition['condition_detail']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="input_date">Input Date:</label>
                                <input type="date" class="form-control" id="input_date" name="input_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="img_loc">Image Location:</label>
                                <input type="text" class="form-control" id="img_loc" name="img_loc">
                            </div>
                            <button type="submit" class="btn btn-success btn-lg btn-block" name="submit_plant_info">
                                <i class="fas fa-save mr-2"></i>
                                Save Plant Information
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const resultsContainer = document.getElementById('resultsContainer');
    const plantInfoForm = document.getElementById('plantInfoForm');
    const plantConditionSelect = document.getElementById('plant_condition_id');
    const imgLocInput = document.getElementById('img_loc');
    const plantLabelInput = document.getElementById('plant_label');
    const plantInfoSection = document.getElementById('plantInfoSection');
    const growthIdSelect = document.getElementById('growth_id');
    
    // Initially hide the plant info section
    if (plantInfoSection) {
        plantInfoSection.style.display = 'none';
    }
    
    // Function to extract plant condition ID from class name
    function getConditionIdFromClass(className) {
        const classToConditionMap = {
            'melon-ripe': 6,        // Berbuah (Matang)
            'melon-unripe': 5,      // Berbuah (Belum Matang)
            'melon-flower-bud': 2,  // Bunga (Kuncup)
            'melon-flower-bloom': 3, // Bunga (Bunga)
            'melon-flower-wilt': 4, // Bunga (Layu)
            'melon-normal': 1,      // Normal
            'melon-stunted': 7,     // Kerdil
            'melon-wilt': 8,        // Layu
            'melon-dead': 9         // Mati
        };
        
        return classToConditionMap[className] || 1; // Default to 1 (Normal) if not found
    }
    
    // Generate plant label based on growth ID and timestamp
    function generatePlantLabel(growthId) {
        const timestamp = new Date().getTime();
        return `Plant-${growthId}-${timestamp}`;
    }
    
    // Add event listener for growth ID select
    if (growthIdSelect) {
        growthIdSelect.addEventListener('change', function() {
            const selectedGrowthId = this.value;
            // Don't auto-generate plant label anymore
            // plantLabelInput.value = generatePlantLabel(selectedGrowthId);
        });
        
        // No need to set initial value
        // if (growthIdSelect.value) {
        //     plantLabelInput.value = generatePlantLabel(growthIdSelect.value);
        // }
    }
    
    // Custom file input - restore image preview functionality
    const fileInput = document.querySelector('.custom-file-input');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file...';
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;

            // Preview image
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var previewContainer = document.querySelector('.preview-container');
                    if (!previewContainer) {
                        previewContainer = document.createElement('div');
                        previewContainer.className = 'preview-container';
                        var dropzoneForm = document.querySelector('.dropzone-form');
                        dropzoneForm.insertBefore(previewContainer, dropzoneForm.querySelector('button'));
                    } else {
                        // Clear the existing preview
                        previewContainer.innerHTML = '';
                    }

                    var preview = document.createElement('img');
                    preview.className = 'img-fluid rounded shadow mt-3';
                    preview.src = e.target.result;
                    preview.alt = 'Image Preview';
                    previewContainer.appendChild(preview);
                }
                reader.readAsDataURL(this.files[0]);
            }
        })
    }
    
    // Hide plant info form initially
    if (plantInfoForm) {
        plantInfoForm.style.display = 'block';
    }
    
    uploadForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Don't hide any previous plant info section
        // if (plantInfoSection) {
        //     plantInfoSection.style.display = 'block';
        // }
        
        // Check if file is selected
        const fileInput = document.getElementById('image');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select an image file first.');
            return;
        }
        
        const formData = new FormData(this);
        
        // Show loading indicator and hide results
        loadingIndicator.style.display = 'block';
        resultsContainer.style.display = 'none';
        
        // Disable the submit button to prevent multiple submissions
        const submitButton = document.getElementById('detectButton');
        if (submitButton) {
            submitButton.disabled = true;
        }
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extract the results section
            const newResults = doc.getElementById('resultsContainer');
            
            if (newResults) {
                // Update only the results container's contents
                resultsContainer.innerHTML = newResults.innerHTML;
                resultsContainer.style.display = 'block';
                loadingIndicator.style.display = 'none';
                
                // Find the first detection and its class
                const firstDetection = resultsContainer.querySelector('tr.detection-row');
                if (firstDetection) {
                    const detectionClass = firstDetection.querySelector('td:nth-child(2)').textContent.trim();
                    const conditionId = getConditionIdFromClass(detectionClass);
                    
                    // Set the plant condition select value
                    if (plantConditionSelect) {
                        for (let i = 0; i < plantConditionSelect.options.length; i++) {
                            if (plantConditionSelect.options[i].value == conditionId) {
                                plantConditionSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                    
                    // Clear the plant label input field (let the user fill it)
                    if (plantLabelInput) {
                        plantLabelInput.value = '';
                    }
                    
                    // Set the image location from the image src without the domain
                    const resultImageElement = resultsContainer.querySelector('.img-fluid[alt="Detection Result"]');
                    if (resultImageElement && imgLocInput) {
                        // Extract only the path portion, not the full URL
                        const fullUrl = resultImageElement.src;
                        const pathMatch = fullUrl.match(/\/output\/.*\.jpg/);
                        if (pathMatch) {
                            imgLocInput.value = pathMatch[0];
                        }
                    }
                    
                    // Show the plant info section
                    if (plantInfoSection) {
                        plantInfoSection.style.display = 'block';
                        
                        // Scroll to the section
                        // plantInfoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            } else {
                console.error('No results container found in the response');
                resultsContainer.innerHTML = '<div class="alert alert-danger">Could not parse detection results.</div>';
                resultsContainer.style.display = 'block';
                loadingIndicator.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.style.display = 'none';
            resultsContainer.innerHTML = `<div class="alert alert-danger">
                <strong>Error:</strong> ${error.message}
            </div>`;
            resultsContainer.style.display = 'block';
        })
        .finally(() => {
            // Always re-enable the submit button
            if (submitButton) {
                submitButton.disabled = false;
            }
        });
    });
    
    // Add additional information to form when a growth ID is selected
    if (growthIdSelect) {
        const growthInfoContainer = document.createElement('div');
        growthInfoContainer.className = 'growth-info-container mt-3 p-3 border rounded bg-light';
        growthInfoContainer.style.display = 'none';
        plantInfoForm.insertBefore(growthInfoContainer, plantInfoForm.querySelector('.form-group:nth-child(2)').nextSibling);
        
        growthIdSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const growthId = this.value;
            
            // Get the growth information from the options dataset or make an AJAX request
            fetch(`/api/get_growth_info.php?growth_id=${growthId}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        const info = data.data;
                        growthInfoContainer.innerHTML = `
                            <h5>Growth Information</h5>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Greenhouse:</dt>
                                <dd class="col-sm-8">${info.greenhouse_name}</dd>
                                
                                <dt class="col-sm-4">Species:</dt>
                                <dd class="col-sm-8">${info.species}</dd>
                                
                                <dt class="col-sm-4">Start Date:</dt>
                                <dd class="col-sm-8">${info.cultivation_start_date}</dd>
                                
                                <dt class="col-sm-4">End Date:</dt>
                                <dd class="col-sm-8">${info.cultivation_end_date || 'N/A'}</dd>
                                
                                <dt class="col-sm-4">Method:</dt>
                                <dd class="col-sm-8">${info.cultivation_method}</dd>
                                
                                <dt class="col-sm-4">Number of Crops:</dt>
                                <dd class="col-sm-8">${info.number_of_crop}</dd>
                            </dl>
                        `;
                        growthInfoContainer.style.display = 'block';
                    } else {
                        growthInfoContainer.innerHTML = '<div class="alert alert-warning">No growth information available</div>';
                        growthInfoContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching growth info:', error);
                    growthInfoContainer.innerHTML = '<div class="alert alert-danger">Error loading growth information</div>';
                    growthInfoContainer.style.display = 'block';
                });
        });
        
        // Trigger change event on page load if a value is selected
        if (growthIdSelect.value) {
            const event = new Event('change');
            growthIdSelect.dispatchEvent(event);
        }
    }
});

// Alert message handling
document.addEventListener('DOMContentLoaded', function() {
    const alertMessage = document.querySelector('.alert-message');
    if (alertMessage) {
        setTimeout(function() {
            alertMessage.style.display = 'none';
        }, 5000);
    }
});
</script>

<style>
.dropzone-form {
    padding: 1.5rem;
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    text-align: center;
}

.dropzone-form:hover {
    border-color: #007bff;
    background-color: #f1f8ff;
}

.dropzone-form .preview-container {
    margin: 1rem auto;
    width: 100%;
    max-width: 300px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 0.5rem;
    background-color: #fff;
}

.dropzone-form .preview-container img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 0.5rem;
}

.custom-file-label {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.card {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn {
    border-radius: 0.3rem;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.alert {
    border-radius: 0.3rem;
}

.img-fluid {
    transition: transform 0.3s ease;
}

.img-fluid:hover {
    transform: scale(1.02);
}

#loadingIndicator {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.9);
    padding: 2rem;
    border-radius: 0.5rem;
    z-index: 1000;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.card-header .fas {
    width: 20px;
    text-align: center;
}

.btn-group-toggle .btn {
    flex: 1;
    padding: 0.75rem 0.5rem;
    font-size: 0.9rem;
    border-width: 2px;
    transition: all 0.3s ease;
}

.btn-group-toggle .btn:hover {
    transform: translateY(-2px);
}

.btn-group-toggle .btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.btn-group-toggle .btn i {
    font-size: 1rem;
}

.model-description {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.5rem;
}
</style>
