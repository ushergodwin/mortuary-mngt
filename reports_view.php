<?php
$report_type = "daily";
if (isset($_GET['rt'])) {
    $report_type = $_GET['rt'];
}
$today = date('Y-m-d');
$weekly_date = date('Y-m-d', strtotime('-1 week'));
$monthly_date = date('Y-m-d', strtotime('-1 month'));

$incoming_deceased = [];
$servername = "localhost";
$username = "root";
$password = "";
$no_of_in = $no_of_out = $no_invoices = $no_of_bills = $no_of_beds = $no_of_rooms = 0;
// Create connection
$conn = mysqli_connect($servername, $username, $password, "mogue_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$incoming_deceased_query = "SELECT * FROM incoming_deceased INNER JOIN relatives_info ON incoming_deceased.relative_name = relatives_info.id 
 INNER JOIN beds ON incoming_deceased.bed = beds.id INNER JOIN rooms ON incoming_deceased.room = rooms.id WHERE incoming_deceased.date ='$today';";

if ($report_type == 'weekly') {

    $incoming_deceased_query = "SELECT * FROM incoming_deceased INNER JOIN relatives_info ON incoming_deceased.relative_name = relatives_info.id 
 INNER JOIN beds ON incoming_deceased.bed = beds.id INNER JOIN rooms ON incoming_deceased.room = rooms.id WHERE incoming_deceased.date  >= '$weekly_date' AND incoming_deceased.date <= '$today';";
}

if ($report_type == 'monthly') {
    $incoming_deceased_query = "SELECT * FROM incoming_deceased INNER JOIN relatives_info ON incoming_deceased.relative_name = relatives_info.id 
 INNER JOIN beds ON incoming_deceased.bed = beds.id INNER JOIN rooms ON incoming_deceased.room = rooms.id WHERE incoming_deceased.date >= '$monthly_date' AND incoming_deceased.date  <= '$today';";
}


// outgoing 
$outgoing_deceased_query = "SELECT outgoing_deceased.car_out_number, outgoing_deceased.date, incoming_deceased.*, 
rooms.name, beds.number, relatives_info.first_relative_full_name  FROM outgoing_deceased INNER JOIN incoming_deceased ON 
 outgoing_deceased.fullname = incoming_deceased.id INNER JOIN beds ON incoming_deceased.bed = beds.id INNER JOIN rooms 
 ON incoming_deceased.room = rooms.id INNER JOIN relatives_info ON incoming_deceased.relative_name = relatives_info.id WHERE outgoing_deceased.date = '$today';";

if ($report_type == 'weekly') {
    $outgoing_deceased_query = "SELECT outgoing_deceased.car_out_number, outgoing_deceased.date, incoming_deceased.*, 
rooms.name, beds.number, relatives_info.first_relative_full_name  FROM outgoing_deceased INNER JOIN incoming_deceased ON 
 outgoing_deceased.fullname = incoming_deceased.id INNER JOIN beds ON incoming_deceased.bed = beds.id INNER JOIN rooms 
 ON incoming_deceased.room = rooms.id INNER JOIN relatives_info ON incoming_deceased.relative_name = relatives_info.id WHERE outgoing_deceased.date >= '$weekly_date' AND outgoing_deceased.date <= '$today';";
}

if ($report_type == 'monthly') {
    $outgoing_deceased_query = "SELECT outgoing_deceased.car_out_number, outgoing_deceased.date, incoming_deceased.*, 
rooms.name, beds.number, relatives_info.first_relative_full_name  FROM outgoing_deceased INNER JOIN incoming_deceased ON 
 outgoing_deceased.fullname = incoming_deceased.id INNER JOIN beds ON incoming_deceased.bed = beds.id INNER JOIN rooms 
 ON incoming_deceased.room = rooms.id INNER JOIN relatives_info ON incoming_deceased.relative_name = relatives_info.id WHERE outgoing_deceased.date >= '$monthly_date' AND outgoing_deceased.date <= '$today';";
}

//invoices 
$invoices_query = "SELECT * FROM `invoices` INNER JOIN incoming_deceased ON invoices.deceased = incoming_deceased.id INNER JOIN relatives_info ON invoices.relative = relatives_info.id
WHERE invoices.date = '$today';";

if ($report_type == 'weekly') {
    $invoices_query = "SELECT * FROM `invoices` INNER JOIN incoming_deceased ON invoices.deceased = incoming_deceased.id INNER JOIN relatives_info ON invoices.relative = relatives_info.id
WHERE invoices.date >= '$weekly_date' AND invoices.date  <= '$today';";
}

if ($report_type == 'monthly') {
    $invoices_query = "SELECT * FROM `invoices` INNER JOIN incoming_deceased ON invoices.deceased = incoming_deceased.id INNER JOIN relatives_info ON invoices.relative = relatives_info.id
WHERE invoices.date >= '$monthly_date' AND invoices.date  <= '$today';";
}

// bills 
$bills_query = "SELECT * FROM `bill` INNER JOIN invoices ON bill.total = invoices.id 
INNER JOIN incoming_deceased ON invoices.deceased = incoming_deceased.id WHERE date(bill.date) = '$today';";

if ($report_type == 'weekly') {
    $bills_query = "SELECT * FROM `bill` INNER JOIN invoices ON bill.total = invoices.id 
    INNER JOIN incoming_deceased ON invoices.deceased = incoming_deceased.id WHERE date(bill.date)>= '$weekly_date' AND date(bill.date)  <= '$today';";
}

if ($report_type == 'monthly') {
    $bills_query = "SELECT * FROM `bill` INNER JOIN invoices ON bill.total = invoices.id 
    INNER JOIN incoming_deceased ON invoices.deceased = incoming_deceased.id WHERE date(bill.date) >= '$monthly_date' AND date(bill.date) <= '$today';";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kampala City Mortuary <?= ucfirst($report_type) ?> Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-8">

            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <label class="input-group-text">Report Type</label>
                    <form action="./reports_view.php?t=1700465498">
                        <select class="form-select" name="rt" onchange="this.form.submit()">
                            <option value="daily" <?= $report_type === 'daily' ? 'selected' : '' ?>>Daily</option>
                            <option value="weekly" <?= $report_type === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                            <option value="monthly" <?= $report_type === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                        </select>
                    </form>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">Print
                        Report</button>
                </div>
            </div>
        </div>
        <div class="card card-body shadow mt-4">
            <div class="row mt-3">
                <div class="col-md-10">
                    <h5>Incoming Deceased </h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Tag Number</th>
                                <th>Room</th>
                                <th>Relative</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $result = $conn->query($incoming_deceased_query); ?>
                            <?php if ($result->num_rows > 0) : ?>

                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['fullname'] ?></td>
                                        <td><?= $row['gender'] ?></td>
                                        <td><?= $row['tag_number'] ?></td>
                                        <td><?= $row['name'] ?>:<?= $row['number'] ?></td>
                                        <td><?= $row['first_relative_full_name'] ?></td>
                                    </tr>
                                    <?php $no_of_in += 1; ?>
                                    <?php $no_of_beds += 1; ?>
                                    <?php $no_of_rooms += 1; ?>
                                <?php endwhile ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                    <hr />
                    <h5>Outgoing Deceased </h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Tag Number</th>
                                <th>Room</th>
                                <th>Relative</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $result = $conn->query($outgoing_deceased_query); ?>
                            <?= $conn->error; ?>
                            <?php if ($result->num_rows > 0) : ?>

                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['fullname'] ?></td>
                                        <td><?= $row['gender'] ?></td>
                                        <td><?= $row['tag_number'] ?></td>
                                        <td><?= $row['name'] ?>:<?= $row['number'] ?></td>
                                        <td><?= $row['first_relative_full_name'] ?></td>
                                    </tr>
                                    <?php $no_of_out += 1; ?>
                                <?php endwhile ?>
                            <?php endif ?>
                        </tbody>
                    </table>

                    <hr />
                    <h5>Invoices </h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service</th>
                                <th>Amount</th>
                                <th>Deceased</th>
                                <th>Relative</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $result = $conn->query($invoices_query); ?>
                            <?= $conn->error; ?>
                            <?php if ($result->num_rows > 0) : ?>

                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['services'] ?></td>
                                        <td>UGX: <?= number_format($row['total'], 2) ?></td>
                                        <td><?= $row['fullname'] ?></td>
                                        <td><?= $row['first_relative_full_name'] ?></td>
                                    </tr>
                                    <?php $no_invoices += 1; ?>
                                <?php endwhile ?>
                            <?php endif ?>
                        </tbody>
                    </table>


                    <hr />
                    <h5>Bills </h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service Amount</th>
                                <th>Amount Paid </th>
                                <th>Balance </th>
                                <th>Deceased</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $result = $conn->query($bills_query); ?>
                            <?= $conn->error; ?>
                            <?php if ($result->num_rows > 0) : ?>

                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td>UGX: <?= number_format($row['total'], 2) ?></td>
                                        <td>UGX: <?= number_format($row['paid_amount'], 2) ?></td>
                                        <td>UGX: <?= number_format(($row['total'] - $row['paid_amount']), 2) ?></td>
                                        <td><?= $row['fullname'] ?></td>
                                    </tr>
                                    <?php $no_of_bills += 1; ?>
                                <?php endwhile ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2 mt-4">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                No of Incoming Deceased <span class="badge bg-secondary"><?= $no_of_in ?></span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                No of Outgoing Deceased <span class="badge bg-secondary"><?= $no_of_out ?></span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                No Invoices <span class="badge bg-secondary"><?= $no_invoices ?></span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                No of Bills <span class="badge bg-secondary"><?= $no_of_bills ?></span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                No Rooms Occupied <span class="badge bg-secondary"><?= $no_of_rooms ?></span>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                No Beds Occupied <span class="badge bg-secondary"><?= $no_of_in ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>