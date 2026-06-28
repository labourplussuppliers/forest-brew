<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Staff Profile

            </h2>

            <p class="text-muted mb-0">

                Employee information, attendance and activity.

            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/admin/staff"
            class="btn btn-outline-secondary">

            Back

        </a>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-body text-center">

                    <img
                        src="<?= !empty($employee['avatar']) ? asset($employee['avatar']) : asset('images/default-avatar.png') ?>"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        style="object-fit:cover;">

                    <h4>

                        <?= e($employee['first_name']) ?>

                        <?= e($employee['last_name']) ?>

                    </h4>

                    <p class="text-muted">

                        <?= ucfirst(

                            str_replace(

                                '_',

                                ' ',

                                $employee['role']

                            )

                        ) ?>

                    </p>

                    <?php if($employee['status']): ?>

                        <span class="badge bg-success">

                            Active

                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">

                            Inactive

                        </span>

                    <?php endif; ?>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Contact Information

                    </h5>

                </div>

                <div class="card-body">

                    <p>

                        <strong>Email</strong>

                        <br>

                        <?= e($employee['email']) ?>

                    </p>

                    <p>

                        <strong>Phone</strong>

                        <br>

                        <?= e($employee['phone']) ?>

                    </p>

                    <p>

                        <strong>Joined</strong>

                        <br>

                        <?= date(

                            'd M Y',

                            strtotime($employee['created_at'])

                        ) ?>

                    </p>

                    <p class="mb-0">

                        <strong>Last Login</strong>

                        <br>

                        <?= !empty($employee['last_login'])

                            ? date(

                                'd M Y h:i A',

                                strtotime($employee['last_login'])

                            )

                            : 'Never'

                        ?>

                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="row">

                <div class="col-md-4 mb-4">

                    <div class="card shadow-sm border-0 text-center">

                        <div class="card-body">

                            <h3 class="text-success">

                                <?= !empty($attendance)

                                    ? 'Present'

                                    : 'Absent'

                                ?>

                            </h3>

                            <small>

                                Today's Status

                            </small>

                        </div>

                    </div>

                </div>

                <div class="col-md-4 mb-4">

                    <div class="card shadow-sm border-0 text-center">

                        <div class="card-body">

                            <h3 class="text-primary">

                                <?= count($salary) ?>

                            </h3>

                            <small>

                                Salary Records

                            </small>

                        </div>

                    </div>

                </div>

                <div class="col-md-4 mb-4">

                    <div class="card shadow-sm border-0 text-center">

                        <div class="card-body">

                            <h3 class="text-warning">

                                <?= count($leaves) ?>

                            </h3>

                            <small>

                                Leave Requests

                            </small>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Today's Attendance

                    </h5>

                </div>

                <div class="card-body">

                    <?php if(empty($attendance)): ?>

                        <p class="text-muted mb-0">

                            No attendance recorded today.

                        </p>

                    <?php else: ?>

                        <div class="row">

                            <div class="col-md-6">

                                <strong>

                                    Clock In

                                </strong>

                                <br>

                                <?= date(

                                    'h:i A',

                                    strtotime(

                                        $attendance['clock_in']

                                    )

                                ) ?>

                            </div>

                            <div class="col-md-6">

                                <strong>

                                    Clock Out

                                </strong>

                                <br>

                                <?= !empty($attendance['clock_out'])

                                    ? date(

                                        'h:i A',

                                        strtotime(

                                            $attendance['clock_out']

                                        )

                                    )

                                    : '-'

                                ?>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Recent Salary Records

                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table mb-0">

                        <thead>

                            <tr>

                                <th>

                                    Month

                                </th>

                                <th>

                                    Amount

                                </th>

                                <th>

                                    Status

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php if(empty($salary)): ?>

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center">

                                    No salary records found.

                                </td>

                            </tr>

                        <?php endif; ?>

                        <?php foreach(array_slice($salary,0,5) as $record): ?>

                            <tr>

                                <td>

                                    <?= e($record['salary_month']) ?>

                                </td>

                                <td>

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format(

                                        $record['amount'],

                                        2

                                    ) ?>

                                </td>

                                <td>

                                    <span class="badge bg-success">

                                        <?= e($record['status']) ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Recent Login Activity

                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table mb-0">

                        <thead>

                            <tr>

                                <th>

                                    Login Time

                                </th>

                                <th>

                                    IP Address

                                </th>

                                <th>

                                    Device

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php if(empty($logins)): ?>

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center">

                                    No login history available.

                                </td>

                            </tr>

                        <?php endif; ?>

                        <?php foreach(array_slice($logins,0,10) as $login): ?>

                            <tr>

                                <td>

                                    <?= date(

                                        'd M Y h:i A',

                                        strtotime(

                                            $login['login_at']

                                        )

                                    ) ?>

                                </td>

                                <td>

                                    <?= e($login['ip_address']) ?>

                                </td>

                                <td>

                                    <?= e($login['device']) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>