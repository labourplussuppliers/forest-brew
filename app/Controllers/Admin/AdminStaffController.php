<?php

class AdminStaffController extends Controller
{
    private Staff $staffModel;

    public function __construct()
    {
        parent::__construct();

        $this->staffModel = new Staff($this->db);

        if (
            !$this->session->check() ||
            !$this->session->user()['is_admin']
        ) {
            $this->redirect(BASE_URL . '/login');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Staff List
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $keyword = trim(
            $this->request->input('search')
        );

        if ($keyword !== '') {

            $staff = $this->staffModel
                ->search($keyword);

        } else {

            $staff = $this->staffModel
                ->all();

        }

        $this->view(

            'admin/staff/index',

            [

                'pageTitle' => 'Staff',

                'staff' => $staff

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Staff Details
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ): void
    {
        $employee = $this->staffModel
            ->find($id);

        if (!$employee) {

            $this->response->abort(404);

        }

        $attendance = $this->staffModel
            ->attendance(

                $id,

                date('Y-m-d')

            );

        $salary = $this->staffModel
            ->salary($id);

        $leaves = $this->staffModel
            ->leaves($id);

        $logins = $this->staffModel
            ->loginHistory($id);

        $this->view(

            'admin/staff/show',

            [

                'pageTitle' => 'Staff Details',

                'employee' => $employee,

                'attendance' => $attendance,

                'salary' => $salary,

                'leaves' => $leaves,

                'logins' => $logins

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    public function attendance(): void
    {
        $records = $this->staffModel
            ->todayAttendance();

        $this->view(

            'admin/staff/attendance',

            [

                'pageTitle' => 'Attendance',

                'records' => $records

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Clock In
    |--------------------------------------------------------------------------
    */

    public function clockIn(
        int $id
    ): void
    {
        $this->staffModel
            ->clockIn($id);

        setFlash(

            'success',

            'Staff clocked in successfully.'

        );

        $this->redirect(

            BASE_URL .
            '/admin/staff/attendance'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Clock Out
    |--------------------------------------------------------------------------
    */

    public function clockOut(
        int $id
    ): void
    {
        $this->staffModel
            ->clockOut($id);

        setFlash(

            'success',

            'Staff clocked out successfully.'

        );

        $this->redirect(

            BASE_URL .
            '/admin/staff/attendance'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Salary Records
    |--------------------------------------------------------------------------
    */

    public function salary(
        int $id
    ): void
    {
        $employee = $this->staffModel
            ->find($id);

        $salary = $this->staffModel
            ->salary($id);

        $this->view(

            'admin/staff/salary',

            [

                'pageTitle' => 'Salary Records',

                'employee' => $employee,

                'salary' => $salary

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Requests
    |--------------------------------------------------------------------------
    */

    public function leaves(
        int $id
    ): void
    {
        $employee = $this->staffModel
            ->find($id);

        $leaves = $this->staffModel
            ->leaves($id);

        $this->view(

            'admin/staff/leaves',

            [

                'pageTitle' => 'Leave Requests',

                'employee' => $employee,

                'leaves' => $leaves

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Top Cashiers
    |--------------------------------------------------------------------------
    */

    public function performance(): void
    {
        $cashiers = $this->staffModel
            ->topCashiers();

        $this->view(

            'admin/staff/performance',

            [

                'pageTitle' => 'Performance',

                'cashiers' => $cashiers

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics API
    |--------------------------------------------------------------------------
    */

    public function statistics(): void
    {
        $this->response->json(

            $this->staffModel
                ->statistics()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Staff
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): void
    {
        $this->staffModel
            ->delete($id);

        setFlash(

            'success',

            'Staff deleted successfully.'

        );

        $this->redirect(

            BASE_URL .
            '/admin/staff'

        );
    }
}