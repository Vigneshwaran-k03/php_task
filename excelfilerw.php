<?php
require 'vendor/autoload.php'; //autoload the spreadsheet and other needed lib
use PhpOffice\PhpSpreadsheet\IOFactory;//used to load a excel file
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;//used to writ and read a excel file

// Handle actions
$action = $_POST['action'] ?? $_GET['action'] ?? ''; //get a action parameter

if ($action === 'download') {
    $file = __DIR__ . "/employee.xlsx"; //path of the file 
    if (file_exists($file)) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="employee_updated.xlsx"');
        readfile($file);
        exit;
    }
}

if ($action === 'save' && isset($_POST['data'])) { 
    $file = __DIR__ . "/employee.xlsx";
    if (file_exists($file)) {
        try {
            $spreadsheet = IOFactory::load($file);// load the file in execting memory
            $sheet = $spreadsheet->getActiveSheet();
            $data = json_decode($_POST['data'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo 'JSON Error: ' . json_last_error_msg();
                exit;
            }

            foreach ($data as $rowIndex => $rowData) {
                foreach ($rowData as $colIndex => $cellValue) {
                    // Convert column index (0,1,2...) to Excel letter (A,B,C...)
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                    $cellRef = $colLetter . ($rowIndex + 1); // Example: A1, B2
                    $sheet->setCellValue($cellRef, $cellValue);
                }
            }
            

            $writer = new Xlsx($spreadsheet);
            $writer->save($file);
            echo 'success';
            exit;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    } else {
        echo 'Error: Excel file not found';
        exit;
    }
}

// Load employee file
$file = __DIR__ . "/employee.xlsx";
$data = [];
if (file_exists($file)) {
    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
    } catch (Exception $e) {
        $error = 'Error reading Excel file: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Excel Manager</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 0 auto; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .btn:hover { background: #45a049; }
        .edit-btn { background: #2196F3; }
        .download-btn { background: #FF9800; }
    </style>
</head>
<body>
    <h1>Employee Excel Manager</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!empty($data)): ?>
        <table id="data-table" border="1" style="width: 100%;" cellspacing="5" cellpadding="0">
            <?php foreach ($data as $rowIndex => $row): ?>
                <tr>
                    <td style="font-weight: bold;"><?php echo $rowIndex + 1; ?></td>
                    <?php foreach ($row as $cell): ?>
                        <td><?php echo htmlspecialchars($cell); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>

        <button class="btn edit-btn" onclick="editData()">Edit Data</button>
        <button class="btn download-btn" onclick="downloadFile()">Download File</button>
    <?php else: ?>
        <p style="color: red;">Employee Excel file not found. Place employee.xlsx in this folder.</p>
    <?php endif; ?>

    <script>
        function editData() {
            const cells = document.querySelectorAll('#data-table td');
            cells.forEach((cell, index) => {
                if (index > 0) {
                    cell.contentEditable = true;
                    cell.style.backgroundColor = '#fff3cd';
                }
            });
            event.target.textContent = 'Save Changes';
            event.target.onclick = saveData;
        }

        function saveData() {
            const table = document.getElementById('data-table');
            const rows = table.querySelectorAll('tr');
            const data = [];

            rows.forEach((row, rowIndex) => {
                const rowData = [];
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, cellIndex) => {
                    if (cellIndex > 0) {
                        rowData.push(cell.textContent);
                    }
                });
                data.push(rowData);
            });

            const saveBtn = event.target;
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;

            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=save&data=' + encodeURIComponent(JSON.stringify(data))
            })
            .then(response => response.text())
            .then(result => {
                if (result === 'success') {
                    alert('Changes saved successfully!');
                    location.reload();
                } else {
                    alert('Error saving changes: ' + result);
                    saveBtn.textContent = 'Save Changes';
                    saveBtn.disabled = false;
                }
            })
            .catch(error => {
                alert('Network error: ' + error.message);
                saveBtn.textContent = 'Save Changes';
                saveBtn.disabled = false;
            });
        }

        function downloadFile() {
            window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?action=download';
        }
    </script>
</body>
</html>
