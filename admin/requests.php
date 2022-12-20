<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Request History
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Sales</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php
            if(isset($_SESSION['error'])){
                echo "
          <div class='callout callout-danger text-center'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
                unset($_SESSION['error']);
            }

            if(isset($_SESSION['success'])){
                echo "
          <div class='callout callout-success text-center'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
                unset($_SESSION['success']);
            }
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <div class="pull-right">
                                <!--                                <form method="POST" class="form-inline" action="sales_print.php">-->
                                <!--                                    <div class="input-group">-->
                                <!--                                        <div class="input-group-addon">-->
                                <!--                                            <i class="fa fa-calendar"></i>-->
                                <!--                                        </div>-->
                                <!--                                        <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range">-->
                                <!--                                    </div>-->
                                <!--                                    <button type="submit" class="btn btn-success btn-sm btn-flat" name="print"><span class="glyphicon glyphicon-print"></span> Print</button>-->
                                <!--                                </form>-->
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                <th class="hidden"></th>
                                <th>Date</th>
                                <th>UserName</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Reference Link</th>
                                <th>Status</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                <?php
                                $conn = $pdo->open();
                                try {
                                    $stmt = $conn->prepare("SELECT requests.*, users.firstname AS firstname, users.lastname AS lastname FROM requests LEFT JOIN users ON users.id=requests.user_id WHERE users.status=1 ORDER BY requests.id DESC");
                                    $stmt->execute();
                                    foreach ($stmt as $row):
                                        ?>
                                        <tr>
                                            <td class='hidden'></td>
                                            <td><?= date('M d, Y', strtotime($row['created_on'])) ?></td>
                                            <td><?= $row['firstname'] . ' ' . $row['lastname'] ?></td>
                                            <td><?= $row['product_name'] ?></td>
                                            <td><?= $row['product_details'] ?></td>
                                            <td><?= $row['reference_link'] ?></td>
                                            <td>
                                                <?php if($row['status'] == 0): ?>
                                                    <span class="label label-default badge-pill">No Action</span>
                                                <?php elseif($row['status'] == 1): ?>
                                                    <span class="label label-success badge-pill">Confirmed</span>
                                                <?php else: ?>
                                                    <span class="label label-danger badge-pill">Rejected</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == 0): ?>
                                                    <a class="btn btn-success" href='confirm_request.php?id=<?= $row['id']?>&user_id=<?= $row['user_id']?>'>
                                                        Confirm
                                                    </a>
                                                    <a class="btn btn-danger" href='decline_request_code.php?id=<?= $row['id']?>&user_id=<?= $row['user_id']?>'>
                                                        Decline
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                $pdo->close();
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <?php include 'includes/footer.php'; ?>
    <?php include '../includes/profile_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<!-- Date Picker -->
<script>

</script>
<script>

</script>
</body>
</html>
