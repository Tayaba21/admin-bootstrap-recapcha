<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <?php
    $user_id = $_GET['user_id'];
    $request_id = $_GET['id'];

    $conn = $pdo->open();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->execute(['id' => $user_id]);

    foreach ($stmt as $row) {
        $user = $row;
    }

    $conn = $pdo->open();
    $stmt = $conn->prepare("SELECT * FROM requests WHERE id=:id");
    $stmt->execute(['id' => $request_id]);

    foreach ($stmt as $row) {
        $request = $row;
    }
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Confirm Request For <?= $request['product_name'] ?>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Sales</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php
            if (isset($_SESSION['error'])) {
                echo "
          <div class='callout callout-danger text-center'>
            <p>" . $_SESSION['error'] . "</p> 
          </div>
        ";
                unset($_SESSION['error']);
            }

            if (isset($_SESSION['success'])) {
                echo "
          <div class='callout callout-success text-center'>
            <p>" . $_SESSION['success'] . "</p> 
          </div>
        ";
                unset($_SESSION['success']);
            }
            ?>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <form method="POST" action="confirm_request_code.php">
                            <div class="form-group">
                                <input type="hidden" name="user_id" value="<?= $user['id']?>">
                                <input type="hidden" name="email" value="<?= $user['email'] ?>">
                                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                <input type="hidden" name="name" value="<?= $user['firstname'] . ' ' . $user['lastname']  ?>">
                                <textarea name="message" class="form-control" rows="5"
                                              placeholder="Offer of Admin eg. Price etc"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm btn-flat" name="print">
                                Send
                            </button>
                        </form>

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
    $(function () {
        //Date picker
        $('#datepicker_add').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        })
        $('#datepicker_edit').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        })

        //Timepicker
        $('.timepicker').timepicker({
            showInputs: false
        })

        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'})
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )

    });
</script>
<script>
    $(function () {
        $(document).on('click', '.transact', function (e) {
            e.preventDefault();
            $('#transaction').modal('show');
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: 'transact.php',
                data: {id: id},
                dataType: 'json',
                success: function (response) {
                    $('#date').html(response.date);
                    $('#transid').html(response.transaction);
                    $('#detail').prepend(response.list);
                    $('#total').html(response.total);
                }
            });
        });

        $("#transaction").on("hidden.bs.modal", function () {
            $('.prepend_items').remove();
        });
    });
</script>
</body>
</html>
