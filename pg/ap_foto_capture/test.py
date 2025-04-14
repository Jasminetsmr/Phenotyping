import cv2

cam1 = cv2.VideoCapture(0)

while True:
    _, frame = cam1.read()
    cv2.imshow("frame", frame)
    key = cv2.waitKey(1)

    if key== 27:
        break


cam1.release()
cv2.destroyAllWindows()