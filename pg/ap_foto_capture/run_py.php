<?php 
                  if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $tanaman = $_POST["tanaman-dropdown"];
                    $blok = $_POST["blok-dropdown"];
                    $tray = $_POST["tray-dropdown"];
                                
                    $command = "python3 capture.py " . escapeshellarg($tanaman) . " " . escapeshellarg($blok) . " " . escapeshellarg($tray);

                    // Execute the Python script
                    $output = shell_exec($command);
              }
                ?>