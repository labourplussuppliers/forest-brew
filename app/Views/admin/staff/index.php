<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Staff Management

            </h2>

            <p class="text-muted mb-0">

                Manage employees, roles and attendance.

            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/admin/staff/create"
            class="btn btn-primary">

            <i class="fa-solid fa-user-plus"></i>

            Add Staff

        </a>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-lg-10">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by name, email or phone..."
                            value="<?= e($_GET['search'] ?? '') ?>">

                    </div>

                    <div class="col-lg-2">

                        <button
                            class="btn btn-primary w-100">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="70">

                            ID

                        </th>

                        <th>

                            Staff

                        </th>

                        <th>

                            Role

                        </th>

                        <th>

                            Phone

                        </th>

                        <th>

                            Status

                        </th>

                        <th>

                            Last Login

                        </th>

                        <th width="260">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($staff)): ?>

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-5">

                            No staff members found.

                        </td>

                    </tr>

                <?php endif; ?>

                <?php foreach($staff as $employee): ?>

                    <tr>

                        <td>

                            <?= $employee['id'] ?>

                        </td>

                        <td>

                            <div class="fw-semibold">

                                <?= e($employee['first_name']) ?>

                                <?= e($employee['last_name']) ?>

                            </div>

                            <small class="text-muted">

                                <?= e($employee['email']) ?>

                            </small>

                        </td>

                        <td>

                            <?php

                            $role = ucfirst(

                                str_replace(

                                    '_',

                                    ' ',

                                    $employee['role']

                                )

                            );

                            ?>

                            <span class="badge bg-primary">

                                <?= e($role) ?>

                            </span>

                        </td>

                        <td>

                            <?= e($employee['phone']) ?>

                        </td>

                        <td>

                            <?php if($employee['status']): ?>

                                <span class="badge bg-success">

                                    Active

                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?= !empty($employee['last_login'])

                                ? date(

                                    'd M Y h:i A',

                                    strtotime(

                                        $employee['last_login']

                                    )

                                )

                                : '-'

                            ?>

                        </td>

                        <td>

                            <div class="btn-group">

                                <a
                                    href="<?= BASE_URL ?>/admin/staff/<?= $employee['id'] ?>"
                                    class="btn btn-sm btn-primary"
                                    title="View Profile">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/staff/attendance"
                                    class="btn btn-sm btn-success"
                                    title="Attendance">

                                    <i class="fa-solid fa-user-check"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/staff/salary/<?= $employee['id'] ?>"
                                    class="btn btn-sm btn-warning"
                                    title="Salary">

                                    <i class="fa-solid fa-money-bill-wave"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/staff/leaves/<?= $employee['id'] ?>"
                                    class="btn btn-sm btn-info"
                                    title="Leaves">

                                    <i class="fa-solid fa-calendar-days"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/staff/delete/<?= $employee['id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this staff member?')"
                                    title="Delete">

                                    <i class="fa-solid fa-trash"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>