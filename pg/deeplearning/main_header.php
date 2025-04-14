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
require_once $_SERVER['DOCUMENT_ROOT'] . '/ds_min/pg/login/config.php';

// Make the connection variable consistent
$connection = $mysqli;

// Base paths
$baseDir = realpath(__DIR__ . '/../../');
$resultsDir = $baseDir . '/results';
$outputDir = $baseDir . '/output';

// Add Font Awesome CSS and proper font-face declaration
echo '<style>
@font-face {
    font-family: "FontAwesome";
    src: url("../../dist/font-awesome/fonts/fontawesome-webfont.woff2") format("woff2"),
         url("../../dist/font-awesome/fonts/fontawesome-webfont.woff") format("woff");
    font-weight: normal;
    font-style: normal;
}

/* Fix for Font Awesome icons */
.fa, .fas, .far, .fab {
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome !important;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    transform: translate(0, 0);
}

/* Icon size adjustments */
.card-title .fa, 
.card-header .fa {
    font-size: 18px;
}

h1 .fa, h2 .fa, h3 .fa {
    font-size: 90%;
}

h4 .fa, h5 .fa, h6 .fa {
    font-size: 85%;
}

.btn .fa {
    font-size: 16px;
}

.badge .fa {
    font-size: 12px;
}

label .fa {
    font-size: 16px;
}

.form-text .fa {
    font-size: 14px;
}

.fa-3x {
    font-size: 3em !important;
}

.progress-bar .fa {
    font-size: 14px;
}

/* Modern UI Styles */
body {
    background-color: #f8f9fa;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 16px;
}

.container-fluid {
    padding: 20px;
}

/* Header Styles */
.content-header {
    padding: 15px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.content-header h1 {
    font-size: 28px;
    font-weight: 500;
    margin: 0;
    color: #212529;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
    font-size: 16px;
}

.breadcrumb-item a {
    color: #007bff;
}

/* Card Styles */
.card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 25px;
    overflow: hidden;
    background-color: #ffffff;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid #f0f0f0;
    padding: 15px 20px;
}

.card-header.bg-primary {
    background: linear-gradient(135deg, #2196F3, #1976D2) !important;
}

.card-header.bg-info {
    background: linear-gradient(135deg, #00BCD4, #0097A7) !important;
}

.card-header.bg-success {
    background: linear-gradient(135deg, #4CAF50, #388E3C) !important;
}

.card-title {
    font-weight: 500;
    margin: 0;
    font-size: 20px;
}

.card-body {
    padding: 20px;
    background-color: #ffffff;
}

/* Button Styles */
.btn {
    border-radius: 4px;
    font-weight: 500;
    padding: 10px 18px;
    transition: all 0.2s;
    font-size: 16px;
}

/* Model selection buttons - bigger and more prominent */
.model-selection-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.model-selection-group .btn {
    padding: 12px 20px;
    font-size: 18px;
    text-align: left;
    border-width: 2px;
    display: flex;
    align-items: center;
}

.model-selection-group .btn i {
    font-size: 20px;
    margin-right: 10px;
    width: 24px;
    text-align: center;
}

.model-selection-group .btn.active {
    background-color: #2196F3;
    color: white;
    border-color: #2196F3;
    box-shadow: 0 2px 5px rgba(33, 150, 243, 0.3);
}

/* Improved file upload */
.file-upload-container {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 25px;
    text-align: center;
    margin-bottom: 20px;
    transition: all 0.3s;
    background-color: #f8f9fa;
    cursor: pointer;
    position: relative;
    min-height: 250px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.file-upload-container:hover {
    border-color: #2196F3;
    background-color: #e3f2fd;
}

.file-upload-container i {
    font-size: 48px;
    color: #2196F3;
    margin-bottom: 15px;
}

.file-upload-container p {
    margin-bottom: 5px;
    font-size: 16px;
}

.file-upload-container .file-name {
    font-weight: 500;
    margin-top: 10px;
    word-break: break-all;
}

.file-upload-container input[type="file"] {
    display: none;
}

.image-preview {
    max-width: 100%;
    max-height: 250px;
    margin-top: 15px;
    border-radius: 4px;
    display: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    width: auto;
    height: auto;
}

.file-upload-container.has-file .upload-icon,
.file-upload-container.has-file .upload-text {
    display: none;
}

.file-upload-container.has-file .image-preview {
    display: block;
}

.btn-primary {
    background: linear-gradient(135deg, #2196F3, #1976D2);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1976D2, #0D47A1);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
}

.btn-success {
    background: linear-gradient(135deg, #4CAF50, #388E3C);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #388E3C, #1B5E20);
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.2);
}

.btn-info {
    background: linear-gradient(135deg, #00BCD4, #0097A7);
    border: none;
}

.btn-info:hover {
    background: linear-gradient(135deg, #0097A7, #006064);
    box-shadow: 0 4px 8px rgba(0, 188, 212, 0.2);
}

.btn-outline-primary {
    border-color: #2196F3;
    color: #2196F3;
}

.btn-outline-primary:hover, .btn-outline-primary.active {
    background-color: #2196F3;
    border-color: #2196F3;
    color: white;
}

.btn-lg {
    padding: 10px 20px;
    font-size: 18px;
}

.btn-block {
    width: 100%;
}

/* Form Styles */
.form-control {
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    padding: 8px 12px;
    height: auto;
    font-size: 16px;
}

.form-control:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
}

.custom-file-label {
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    padding: 8px 12px;
    height: auto;
    font-size: 16px;
}

.custom-file-input:focus ~ .custom-file-label {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
}

/* Upload Section Styles */
.text-center {
    text-align: center;
}

.text-muted {
    color: #6c757d !important;
    font-size: 16px;
}

/* Icon Styles */
.card-header .fa {
    width: 20px;
    text-align: center;
    margin-right: 8px;
}

.fa-3x {
    font-size: 3em;
}

.fa-5x {
    font-size: 5em;
}

/* Fix for logo positioning */
.logo-container {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.logo-container img {
    height: 40px;
    margin-right: 10px;
}

.logo-container h1, 
.logo-container h2, 
.logo-container h3 {
    margin: 0;
    font-weight: 500;
}

/* Tab Navigation */
.nav-tabs {
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 20px;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 10px 15px;
    font-size: 16px;
}

.nav-tabs .nav-link.active {
    color: #2196F3;
    border-bottom-color: #2196F3;
    background-color: transparent;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    border-bottom-color: #e9ecef;
}

/* Alert Messages */
.alert {
    border-radius: 4px;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: none;
    font-size: 16px;
}

.alert-success {
    background-color: #e8f5e9;
    color: #388E3C;
}

.alert-danger {
    background-color: #ffebee;
    color: #d32f2f;
}

/* Table Styles */
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    font-size: 16px;
}

.table th {
    font-weight: 500;
    border-top: none;
    border-bottom: 2px solid #e9ecef;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
    border-top: 1px solid #e9ecef;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Loading Indicator */
#loadingIndicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    color: #2196F3;
}

/* Image container styles for consistent display */
.image-container {
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    overflow: hidden;
    background-color: #f8f9fa;
    border-radius: 4px;
    width: 100%;
}

.image-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

/* Detection results table improvements */
.detection-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 16px;
}

.detection-table th {
    background-color: #f8f9fa;
    font-weight: 500;
    text-align: left;
    padding: 12px;
    border-bottom: 2px solid #e9ecef;
}

.detection-table td {
    padding: 12px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.detection-table tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Badge styles for detection classes */
.badge-ripe {
    background-color: #4CAF50;
    color: white;
    font-size: 14px;
}

.badge-unripe {
    background-color: #FFC107;
    color: #212529;
    font-size: 14px;
}

.badge-flower {
    background-color: #9C27B0;
    color: white;
    font-size: 14px;
}

/* Improve collapsible sections */
.collapse-card {
    border: none;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    background-color: #ffffff;
}

.collapse-header {
    padding: 0;
    background-color: #f8f9fa;
}

.collapse-btn {
    width: 100%;
    text-align: left;
    padding: 12px 15px;
    background-color: transparent;
    border: none;
    font-weight: 500;
    color: #212529;
    font-size: 16px;
}

.collapse-btn:hover {
    background-color: #e9ecef;
}

.collapse-btn:focus {
    box-shadow: none;
}

.collapse-body {
    padding: 15px;
    background-color: white;
}

/* Additional styles for headings */
h4, h5, h6 {
    font-weight: 500;
    margin-bottom: 10px;
    color: #212529;
}

h4 {
    font-size: 20px;
}

h5 {
    font-size: 18px;
}

h6 {
    font-size: 16px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-title {
        font-size: 18px;
    }
    
    .btn {
        padding: 6px 12px;
        font-size: 15px;
    }
    
    .container-fluid {
        padding: 15px;
    }
}

/* Make detection result images larger */
.detection-details .image-container {
    height: 300px;
    width: 100%;
    margin-bottom: 10px;
}
</style>';

/* Include Font Awesome from multiple sources to ensure it loads */
echo '<link rel="stylesheet" href="../../dist/css/font-awesome.min.css">';
echo '<link rel="stylesheet" href="../../dist/font-awesome/css/font-awesome.min.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

// Initialize growth info array to prevent undefined variable error
$growthInfoArray = [];

// Check if the tables exist before querying
$tableCheckQuery = "SHOW TABLES LIKE 'tanaman'";
$tableExists = $connection->query($tableCheckQuery)->num_rows > 0;

// Fetch growth information from the database if the user is logged in and table exists
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $tableExists) {
    try {
        $growthInfoQuery = "SELECT t.*, l.id_lokasi, j.jenis_tanaman, v.varietas_tanaman 
                            FROM tanaman t
                            LEFT JOIN lokasi l ON t.id_lokasi = l.id_lokasi
                            LEFT JOIN jenis_tanaman j ON t.id_jenis = j.id_jenis
                            LEFT JOIN varietas_tanaman v ON t.id_varietas = v.id_varietas
                            ORDER BY t.id_tanaman DESC";
        
        $growthInfoResult = $connection->query($growthInfoQuery);
        
        if ($growthInfoResult && $growthInfoResult->num_rows > 0) {
            while ($row = $growthInfoResult->fetch_assoc()) {
                $growthInfoArray[] = $row;
            }
        }
    } catch (Exception $e) {
        // Silently handle the error - we already have an empty array as fallback
        error_log("Error fetching growth info: " . $e->getMessage());
    }
}

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
    $sql = "SELECT b.id_tanaman, b.tgl_semai, b.jenis_tanaman, b.varietas_tanaman, 
            b.populasi, b.petugas_budidaya, g.id_lokasi 
            FROM ds_budidaya b 
            INNER JOIN ds_greenhouse g ON b.id_lokasi = g.id_lokasi 
            ORDER BY b.id_tanaman DESC";
    
    $result = $connection->query($sql);
    $growthInfoArray = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $growthInfoArray[] = $row;
        }
    }
    
    return $growthInfoArray;
}

// Function to get plant conditions (simplified since there's no direct equivalent)
function getPlantConditions($connection) {
    // Creating a simplified array with basic conditions
    return [
        ['plant_condition_id' => 1, 'condition_detail' => 'Healthy'],
        ['plant_condition_id' => 2, 'condition_detail' => 'Diseased'],
        ['plant_condition_id' => 3, 'condition_detail' => 'Pest Damage']
    ];
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
                                $modelPath = str_replace('\\', '/', $baseDir . '/pg/deeplearning/ripeness.pt');
                                // Fallback to roboflow path if file doesn't exist
                                if (!file_exists($modelPath)) {
                                    $modelPath = str_replace('\\', '/', $baseDir . '/pg/roboflow/deeplearning/ripeness.pt');
                                }
                                break;
                            default:
                                $modelPath = str_replace('\\', '/', $baseDir . '/pg/deeplearning/chili_yolov8.pt');
                                // Fallback to roboflow path if file doesn't exist
                                if (!file_exists($modelPath)) {
                                    $modelPath = str_replace('\\', '/', $baseDir . '/pg/roboflow/deeplearning/chili_yolov8.pt');
                                }
                                break;
                        }

                        // Ensure all paths use forward slashes for Python compatibility
                        $rawImagePath = str_replace('\\', '/', $rawImagePath);
                        $detectionFolder = str_replace('\\', '/', $detectionFolder);
                        $resultsDir = str_replace('\\', '/', $resultsDir);
                        
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

def save_image(img, path, desc='image'):
    try:
        cv2.imwrite(path, img)
        print(f'Saved {desc} to: {path}', flush=True)
        return True
    except Exception as e:
        print(f'Error saving {desc}: {str(e)}', flush=True)
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
    # Use raw strings for Windows paths to avoid escape character issues
    model_path = r'$modelPath'
    upload_path = r'$rawImagePath'
    results_dir = r'$detectionFolder'
    
    print(f'Model path: {model_path} (exists: {os.path.exists(model_path)})', flush=True)
    print(f'Upload path: {upload_path} (exists: {os.path.exists(upload_path)})', flush=True)
    print(f'Results dir: {results_dir} (exists: {os.path.exists(results_dir)})', flush=True)
    
    # Create results directory if it doesn't exist
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
    
    print('Starting detection process...', flush=True)
    
    # Load model using YOLOv8
    model = YOLO(model_path)
    
    # Run inference
    results = model(upload_path)
    
    # Convert to JSON
    detections = []
    
    # Process each detection
    for i, result in enumerate(results):
        boxes = result.boxes
        
        for j, box in enumerate(boxes):
            try:
                # Get detection data
                x1, y1, x2, y2 = box.xyxy[0].cpu().numpy()
                conf = float(box.conf[0].cpu().numpy())
                cls = int(box.cls[0].cpu().numpy())
                cls_name = result.names[cls]
                
                # Calculate width and height
                width = x2 - x1
                height = y2 - y1
                
                # Read original image
                original_img = cv2.imread(upload_path)
                if original_img is None:
                    print(f'Error: Could not read image at {upload_path}', flush=True)
                    continue
                
                # Ensure coordinates are within image bounds
                img_height, img_width = original_img.shape[:2]
                x1 = max(0, int(x1))
                y1 = max(0, int(y1))
                x2 = min(img_width, int(x2))
                y2 = min(img_height, int(y2))
                
                # Skip if invalid coordinates
                if x1 >= x2 or y1 >= y2:
                    print(f'Warning: Invalid coordinates for detection {i+1}.{j+1}: ({x1}, {y1}, {x2}, {y2})', flush=True)
                    continue
                
                # Crop image
                cropped_img = original_img[y1:y2, x1:x2]
                
                # Save cropped image
                crop_filename = f'{cls_name}_{i+1}_{j+1}_crop_{timestamp}.jpg'
                crop_path = os.path.join(results_dir, crop_filename)
                
                save_image(cropped_img, crop_path, f'cropped {cls_name}')
                
                # Process melon-ripe images with HSV thresholding
                if cls_name == 'melon-ripe':
                    print(f'Processing melon-ripe image for color analysis...', flush=True)
                    
                    # Process the image
                    processed_img, mask, avg_rgb = process_melon_image(cropped_img, np.array([0, 160, 160]), np.array([30, 240, 255]))
                    
                    # Save processed images
                    processed_filename = f'{cls_name}_{i+1}_{j+1}_processed_{timestamp}.jpg'
                    mask_filename = f'{cls_name}_{i+1}_{j+1}_mask_{timestamp}.jpg'
                    processed_path = os.path.join(results_dir, processed_filename)
                    mask_path = os.path.join(results_dir, mask_filename)
                    save_image(processed_img, processed_path, 'processed melon')
                    save_image(mask, mask_path, 'mask')
                    
                    # Add color data to CSV
                    csv_data.append({
                        'timestamp': timestamp,
                        'detection_id': i * 100 + j + 1,
                        'confidence': conf,
                        'avg_r': avg_rgb[0],
                        'avg_g': avg_rgb[1],
                        'avg_b': avg_rgb[2]
                    })
                
                # Add to detections list
                detections.append({
                    'id': i * 100 + j + 1,
                    'class': cls_name,
                    'confidence': round(float(conf) * 100, 2),
                    'bbox': {
                        'x1': int(x1),
                        'y1': int(y1),
                        'x2': int(x2),
                        'y2': int(y2),
                        'width': int(width),
                        'height': int(height)
                    },
                    'crop_image': crop_filename
                })
                
                print(f'Processed detection {i+1}.{j+1}: {cls_name} (conf: {conf:.2f})', flush=True)
            except Exception as e:
                print(f'Error processing detection {i+1}.{j+1}:', flush=True)
                print(str(e), flush=True)
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
            'timestamp': timestamp,
            'detections': detections
        }, f, indent=2)
    print(f'Saved detection results to: {json_path}', flush=True)
    
    # Save visualization
    output_path = os.path.join(results_dir, f'{timestamp}_result.jpg')
    result_img = results[0].plot()
    save_image(result_img, output_path, 'result visualization')
    
    print(f'Detection complete. Found {len(detections)} objects.', flush=True)
    
    # Print detection results for PHP parsing
    print('DETECTION_RESULTS_START', flush=True)
    json_output = json.dumps({
        'timestamp': timestamp,
        'detections': detections
    })
    print(json_output, flush=True)
    print('DETECTION_RESULTS_END', flush=True)
    
except Exception as e:
    print(f'Error: {str(e)}', flush=True)
    traceback.print_exc(file=sys.stderr)
    sys.exit(1)
";

                        // Write Python script to a temporary file
                        $scriptFile = tempnam(sys_get_temp_dir(), 'detect_') . '.py';
                        file_put_contents($scriptFile, str_replace(['{$timestamp}', '{$modelPath}', '{$rawImagePath}', '{$detectionFolder}'], [$timestamp, $modelPath, $rawImagePath, $detectionFolder], $pythonScript));
                        
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
                            $resultImage = "../../output/" . $detectionFolderName . "/{$timestamp}_result.jpg";
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
    <div class="pb-0 mb-0" style="padding-left: 20px;">
        <div class="row mb-0">
            <div class="col-sm-12">
                <h1 class="m-0 mb-0 pl-2">Plant Phenotyping <b>Deep Learning</b> Analysis</h1>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-0 mt-0">
        <div class="row">
            <div class="col-md-4">
                <!-- Upload Card -->
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white">
                            <i class="fa fa-upload mr-2"></i>
                            Upload Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fa fa-cloud-upload fa-3x text-primary mb-3"></i>
                            <h4>Upload an Image for Detection</h4>
                            <p class="text-muted">Supported: JPG, PNG (Max 50MB)</p>
                        </div>
                        <form id="uploadForm" method="POST" enctype="multipart/form-data" class="dropzone-form">
                            <div class="form-group mb-4">
                                <label class="mb-2"><i class="fa fa-brain mr-2"></i>Select Detection Model</label>
                                <div class="model-selection-group">
                                    <button type="button" class="btn btn-outline-primary active" data-toggle="button" aria-pressed="true" name="model" value="combined">
                                        <i class="fa fa-leaf mr-1"></i>Germination
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-toggle="button" aria-pressed="false" name="model" value="ripeness">
                                        <i class="fa fa-circle mr-1"></i>Viability
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <div class="file-upload-container" style="position: relative; border: 2px dashed #ccc; border-radius: 8px; padding: 30px 20px; text-align: center; background-color: #f8f9fa; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fa fa-cloud-upload fa-3x text-primary mb-3 upload-icon"></i>
                                    <p class="upload-text" style="font-size: 16px; font-weight: 500; color: #495057;">Drag and drop or click anywhere to upload</p>
                                    <input type="file" class="custom-file-input" id="image" name="image" accept=".jpg,.jpeg,.png" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;">
                                    <img src="#" alt="Image Preview" class="image-preview" style="max-width: 100%; max-height: 200px; margin-top: 15px; display: none; border-radius: 5px;">
                                </div>
                                <small class="form-text text-muted mt-2">
                                    <i class="fa fa-info-circle"></i>
                                    Select a clear image for better detection
                                </small>
                            </div>
                            <div class="button-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block mb-3" id="detectBtn" style="border-radius: 8px; padding: 12px; font-weight: 600; box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2); transition: all 0.3s ease; background-color: #2196F3; border: none; color: white;">
                                    <i class="fa fa-search mr-2"></i>
                                    Detect Objects
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-block" id="resetBtn" style="border-radius: 8px; padding: 10px; transition: all 0.3s ease;">
                                    <i class="fa fa-undo mr-2"></i>
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Results Card -->
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title text-white">
                            <i class="fa fa-eye mr-2"></i>
                            Detection Results
                        </h3>
                    </div>
                    
                    <div id="resultsContainer">
                        <?php if (isset($success)): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fa fa-image mr-2"></i>
                                                Original Image
                                            </h5>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="image-container">
                                                <img src="<?php echo htmlspecialchars('../../output/' . $detectionFolderName . '/raw_' . $originalName); ?>" 
                                                     class="rounded shadow" 
                                                     alt="Original Image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fa fa-object-group mr-2"></i>
                                                Detection Result
                                            </h5>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="image-container">
                                                <img src="<?php echo htmlspecialchars($resultImage); ?>" 
                                                     class="rounded shadow" 
                                                     alt="Detection Result">
                                            </div>
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
                                    <?php
                                    // Count occurrences of each class
                                    $classCounts = [];
                                    foreach ($detectionData['detections'] as $detection) {
                                        $class = $detection['class'];
                                        if (!isset($classCounts[$class])) {
                                            $classCounts[$class] = 1;
                                        } else {
                                            $classCounts[$class]++;
                                        }
                                    }
                                    
                                    // Define class-specific colors and icons
                                    $classStyles = [
                                        'cotyledon_appear' => ['bg-success', 'fa fa-leaf', '#28a745'], // Green for cotyledon appearing
                                        'germinated_seed' => ['bg-info', 'fa fa-leaf', '#17a2b8'], // Blue for germinated seed
                                        'not_germinated' => ['bg-danger', 'fa fa-times-circle', '#dc3545'] // Red for not germinated
                                    ];
                                    
                                    if (!empty($classCounts)):
                                    ?>
                                    <div class="card-body border-bottom pb-2 pt-2 bg-light">
                                        <h6 class="font-weight-bold mb-2"><i class="fa fa-chart-pie mr-2"></i>Detection Summary:</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <?php foreach ($classCounts as $class => $count): 
                                                        // Get style for this class or default
                                                        $style = $classStyles[$class] ?? ['bg-info', 'fa fa-tag', '#007bff'];
                                                        $bgClass = $style[0];
                                                        $icon = $style[1];
                                                        $color = $style[2];
                                                        
                                                        // Convert class name to more readable format
                                                        $displayClass = str_replace(['_', '-'], ' ', $class);
                                                        $displayClass = ucwords($displayClass);
                                                    ?>
                                                        <div class="badge <?php echo $bgClass; ?> p-2 m-1" style="font-size: 14px; background-color: <?php echo $color; ?>;">
                                                            <i class="<?php echo $icon; ?> mr-1"></i>
                                                            <?php echo $count; ?> <?php echo ($count > 1 ? 'Objects' : 'Object'); ?>
                                                            <span style="font-size: 13px; margin-left: 5px; font-style: italic;">(<?php echo $displayClass; ?>)</span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <h6 class="font-weight-bold mt-3 mb-2"><i class="fa fa-chart-bar mr-2"></i>Class Distribution:</h6>
                                                <div class="progress" style="height: 30px; margin-top: 5px; border-radius: 5px; overflow: visible;">
                                                    <?php 
                                                    $totalObjects = count($detectionData['detections']);
                                                    foreach ($classCounts as $class => $count): 
                                                        $percentage = ($count / $totalObjects) * 100;
                                                        $style = $classStyles[$class] ?? ['bg-info', 'fa fa-tag', '#007bff'];
                                                        $bgClass = $style[0];
                                                        $icon = $style[1];
                                                        $color = $style[2];
                                                        
                                                        // Convert class name to more readable format
                                                        $displayClass = str_replace(['_', '-'], ' ', $class);
                                                        $displayClass = ucwords($displayClass);
                                                    ?>
                                                        <div class="progress-bar <?php echo $bgClass; ?>" 
                                                             role="progressbar" 
                                                             style="width: <?php echo $percentage; ?>%;
                                                                    background-color: <?php echo $color; ?>;
                                                                    color: #fff;
                                                                    font-weight: bold;
                                                                    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);"
                                                             aria-valuenow="<?php echo $percentage; ?>" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100"
                                                             data-toggle="tooltip"
                                                             data-placement="top"
                                                             title="<?php echo $displayClass; ?>: <?php echo $count; ?> objects (<?php echo round($percentage, 1); ?>%)">
                                                            <?php if ($percentage > 8): ?>
                                                                <i class="<?php echo $icon; ?> mr-1"></i> <?php echo $displayClass; ?> (<?php echo round($percentage, 1); ?>%)
                                                            <?php elseif ($percentage > 4): ?>
                                                                <?php echo round($percentage, 1); ?>%
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <!-- Distribution legend -->
                                                <div class="d-flex flex-wrap mt-2">
                                                    <?php foreach ($classCounts as $class => $count): 
                                                        $percentage = ($count / $totalObjects) * 100;
                                                        $style = $classStyles[$class] ?? ['bg-info', 'fa fa-tag', '#007bff'];
                                                        $bgClass = $style[0];
                                                        $icon = $style[1];
                                                        $color = $style[2];
                                                        // Convert class name to more readable format
                                                        $displayClass = str_replace(['_', '-'], ' ', $class);
                                                        $displayClass = ucwords($displayClass);
                                                    ?>
                                                        <div class="mr-3 mb-2">
                                                            <span class="badge <?php echo $bgClass; ?> mr-1" style="width: 12px; height: 12px; display: inline-block; background-color: <?php echo $color; ?>;"></span>
                                                            <small><strong><?php echo $displayClass; ?></strong>: <?php echo $count; ?> (<?php echo round($percentage, 1); ?>%)</small>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>                                    <div class="card-body">
                                        <div class="table-responsive" style="max-height: 600px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 6px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);">
                                            <table class="table table-hover mb-0">
                                                <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 10; box-shadow: 0 2px 3px rgba(0,0,0,0.1);">
                                                    <tr>
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 15%;">Class</th>
                                                        <th style="width: 20%;">Confidence</th>
                                                        <th style="width: 20%;">Position</th>
                                                        <th style="width: 15%;">Size</th>
                                                        <th style="width: 15%;">Details</th>
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
                                                            <td>
                                                                x1: <?php echo $detection['bbox']['x1']; ?>, 
                                                                y1: <?php echo $detection['bbox']['y1']; ?><br>
                                                                x2: <?php echo $detection['bbox']['x2']; ?>, 
                                                                y2: <?php echo $detection['bbox']['y2']; ?>
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
                                                                    <i class="fa fa-chevron-down"></i>
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
                                                                                <div class="image-container">
                                                                                    <img src="../../output/<?php echo basename($detectionFolder); ?>/<?php echo htmlspecialchars($detection['crop_image']); ?>" 
                                                                                         class="rounded" 
                                                                                         alt="Cropped <?php echo htmlspecialchars($detection['class']); ?>">
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($detection['class'] === 'melon-ripe'): ?>
                                                                            <div class="col-md-4">
                                                                                <h6>Processed Image:</h6>
                                                                                <div class="image-container">
                                                                                    <img src="../../output/<?php echo basename($detectionFolder); ?>/<?php echo htmlspecialchars($detection['processed_image']); ?>" 
                                                                                         class="rounded" 
                                                                                         alt="Processed <?php echo htmlspecialchars($detection['class']); ?>">
                                                                                </div>
                                                                                <h6 class="mt-3">HSV Mask:</h6>
                                                                                <div class="image-container">
                                                                                    <img src="../../output/<?php echo basename($detectionFolder); ?>/<?php echo htmlspecialchars($detection['mask_image']); ?>" 
                                                                                         class="rounded" 
                                                                                         alt="Mask <?php echo htmlspecialchars($detection['class']); ?>">
                                                                                </div>
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
                                <i class="fa fa-images fa-5x mb-3 text-muted"></i>
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
                                        <i class="fa fa-bug mr-2"></i>
                                        Debug Information
                                        <i class="fa fa-chevron-down float-right"></i>
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
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Alert message handling
    const alertMessage = document.querySelector('.alert-message');
    if (alertMessage) {
        setTimeout(function() {
            alertMessage.style.display = 'none';
        }, 5000);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const resultsContainer = document.getElementById('resultsContainer');
    const fileInput = document.getElementById('image');
    const imagePreview = document.querySelector('.image-preview');
    const uploadContainer = document.querySelector('.file-upload-container');
    const uploadIcon = document.querySelector('.upload-icon');
    const uploadText = document.querySelector('.upload-text');
    
    // Handle file selection
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Show the image preview
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                
                // Change the upload container appearance
                uploadIcon.style.display = 'none';
                uploadText.textContent = 'Click to change image';
                uploadContainer.style.padding = '15px';
                uploadContainer.style.backgroundColor = '#ffffff';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Make the entire container clickable
    uploadContainer.addEventListener('click', function(e) {
        if (e.target !== fileInput) {
            fileInput.click();
        }
    });
    
    // Reset button functionality
    document.getElementById('resetBtn').addEventListener('click', function() {
        // Reset the form
        uploadForm.reset();
        
        // Reset the image preview
        imagePreview.src = '#';
        imagePreview.style.display = 'none';
        
        // Reset the upload container appearance
        uploadIcon.style.display = 'block';
        uploadText.textContent = 'Drag and drop or click anywhere to upload';
        uploadContainer.style.padding = '30px 20px';
        uploadContainer.style.backgroundColor = '#f8f9fa';
    });
    
    // Handle form submission with AJAX
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if file is selected
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select an image file first.');
            return;
        }
        
        // Disable detect button
        detectBtn.disabled = true;
        detectBtn.innerHTML = '<div class="d-flex align-items-center justify-content-center"><i class="fa fa-spinner fa-spin mr-2"></i> Processing...</div>';
        detectBtn.style.cursor = 'not-allowed';
        
        // Create form data
        const formData = new FormData(this);
        
        // Get the selected model from the active button
        const activeModelBtn = document.querySelector('.model-selection-group .btn.active');
        if (activeModelBtn) {
            formData.append('model', activeModelBtn.value);
        }
        
        // Send AJAX request
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Get the results container from the response
            const newResults = doc.getElementById('resultsContainer');
            
            if (newResults) {
                // Update the results container
                resultsContainer.innerHTML = newResults.innerHTML;
                resultsContainer.style.display = 'block';
            } else {
                console.error('No results container found in the response');
                resultsContainer.innerHTML = '<div class="alert alert-danger">Error processing the image. Please try again.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultsContainer.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
        })
        .finally(() => {
            // Re-enable detect button
            detectBtn.disabled = false;
            detectBtn.innerHTML = '<i class="fa fa-search mr-2"></i> Detect Objects';
            detectBtn.style.cursor = 'pointer';
            
            // Scroll to results
            resultsContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // Handle model selection buttons
    const modelButtons = document.querySelectorAll('.model-selection-group .btn');
    modelButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            modelButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-pressed', 'false');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            this.setAttribute('aria-pressed', 'true');
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // Handle model selection buttons
    $('.model-selection-group .btn').click(function() {
        // Remove active class from all buttons
        $('.model-selection-group .btn').removeClass('active');
        $('.model-selection-group .btn').attr('aria-pressed', 'false');
        
        // Add active class to clicked button
        $(this).addClass('active');
        $(this).attr('aria-pressed', 'true');
        
        // Add hidden input with selected model value
        const modelValue = $(this).attr('value');
        $('#hiddenModelInput').remove(); // Remove any existing hidden input
        $('<input>').attr({
            type: 'hidden',
            id: 'hiddenModelInput',
            name: 'model',
            value: modelValue
        }).appendTo('#uploadForm');
    });

    // Initialize with combined model selected
    $('<input>').attr({
        type: 'hidden',
        id: 'hiddenModelInput',
        name: 'model',
        value: 'combined'
    }).appendTo('#uploadForm');
    
    // Handle file upload UI
    $('#image').change(function() {
        const fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $('.file-name').text(fileName);
            $('.file-upload-container').addClass('has-file');
            
            // Show image preview
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('.image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        } else {
            $('.file-name').text('Choose file...');
            $('.file-upload-container').removeClass('has-file');
            $('.image-preview').attr('src', '#');
        }
    });
    
    // Make the entire container clickable
    $('.file-upload-container').click(function() {
        $('#image').click();
    });
    
    // Prevent form submission when clicking inside the container
    $('.file-upload-container').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Show loading indicator when form is submitted
    $('#uploadForm').submit(function() {
        if ($('#image').val()) {
            $('#resultsContainer').hide();
        }
    });
    
    // Fix logo positioning
    const fixLogoPositioning = function() {
        // Find the main heading
        const mainHeading = $('h1:contains("Deep Learning")');
        
        // Just ensure the heading is visible without a logo
        if (mainHeading.length) {
            // Make sure any existing logo containers are removed
            $('.logo-container').each(function() {
                // Keep the heading but remove the container
                if ($(this).find('h1').length) {
                    const heading = $(this).find('h1');
                    $(this).parent().prepend(heading);
                }
                $(this).remove();
            });
            
            // Remove any standalone logo images
            $('img[src*="ipb.png"]').each(function() {
                $(this).remove();
            });
        }
    };
    
    // Initialize UI improvements
    setTimeout(function() {
        fixLogoPositioning();
    }, 100);
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
    overflow: hidden;
    background-color: #ffffff;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn {
    border-radius: 0.3rem;
    font-weight: 500;
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

.card-header .fa {
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