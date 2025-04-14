import sys
import cv2
from ultralytics import YOLO
import numpy as np

print("Python version:", sys.version)
print("OpenCV version:", cv2.__version__)
print("Numpy version:", np.__version__)

try:
    model = YOLO('/Users/syauqiarham/Project/Herd/simon/app/content/Melon/deeplearning/combined.pt')
    print("YOLO model loaded successfully")
except Exception as e:
    print("Error loading YOLO model:", str(e))
