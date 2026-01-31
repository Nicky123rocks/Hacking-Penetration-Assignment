<?php
require 'vendor/autoload.php'; 
include 'db_connect.php';

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$year = $_POST['year'] ?? '';
$event_id = $_POST['event_id'] ?? '';

$filter = "";
if ($year) $filter .= " AND a.graduation_year = $year";
if ($event_id) $filter .= " AND er.event_id = $event_id";

$sql = "SELECT a.name, a.email, a.graduation_year, 
            (SELECT COUNT(*) FROM alumni_logins al WHERE al.alumni_id = a.id) AS logins,
            (SELECT COUNT(*) FROM event_registrations er WHERE er.alumni_id = a.id $filter) AS events,
            (SELECT COUNT(*) FROM donations d WHERE d.alumni_id = a.id) AS donations,
            (SELECT SUM(d.amount) FROM donations d WHERE d.alumni_id = a.id) AS amount
        FROM alumni a
        WHERE 1=1 $filter
        ORDER BY a.graduation_year DESC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        $row['name'],
        $row['email'],
        $row['graduation_year'],
        $row['logins'],
        $row['events'],
        $row['donations'],
        number_format($row['amount'] ?? 0, 2)
    ];
}

$headers = ['Name', 'Email', 'Year', 'Logins', 'Event Attendances', 'Donations', 'Total Donated'];

if (isset($_POST['export_pdf'])) {
    $html = '<h2>Alumni Activity Report</h2><table border="1" cellpadding="5"><tr>';
    foreach ($headers as $h) $html .= "<th>$h</th>";
    $html .= '</tr>';
    foreach ($data as $row) {
        $html .= "<tr>";
        foreach ($row as $cell) $html .= "<td>$cell</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("alumni_report.pdf", ["Attachment" => true]);
    exit();

} elseif (isset($_POST['export_excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray([$headers], NULL, 'A1');
    $sheet->fromArray($data, NULL, 'A2');

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="alumni_report.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
    exit();
}
