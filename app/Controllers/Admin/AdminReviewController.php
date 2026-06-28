<?php

class AdminReviewController extends Controller
{
    private Review $reviewModel;

    public function __construct()
    {
        parent::__construct();

        $this->reviewModel = new Review($this->db);

        if (
            !$this->session->check() ||
            !$this->session->user()['is_admin']
        ) {
            $this->redirect(BASE_URL . '/login');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Reviews List
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $status = trim(
            $this->request->input('status')
        );

        switch ($status) {

            case 'pending':

                $reviews = $this->reviewModel
                    ->pending();

                break;

            case 'approved':

                $reviews = $this->db
                    ->query("
                        SELECT
                            r.*,
                            u.first_name,
                            u.last_name,
                            p.name product_name
                        FROM reviews r
                        INNER JOIN users u
                            ON u.id=r.user_id
                        INNER JOIN products p
                            ON p.id=r.product_id
                        WHERE r.status=1
                        ORDER BY r.created_at DESC
                    ")
                    ->fetchAll(PDO::FETCH_ASSOC);

                break;

            default:

                $reviews = $this->db
                    ->query("
                        SELECT
                            r.*,
                            u.first_name,
                            u.last_name,
                            p.name product_name
                        FROM reviews r
                        INNER JOIN users u
                            ON u.id=r.user_id
                        INNER JOIN products p
                            ON p.id=r.product_id
                        ORDER BY r.created_at DESC
                    ")
                    ->fetchAll(PDO::FETCH_ASSOC);

        }

        $this->view(

            'admin/reviews/index',

            [

                'pageTitle' => 'Reviews',

                'reviews' => $reviews

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Review Details
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ): void
    {
        $review = $this->reviewModel
            ->find($id);

        if (!$review) {

            $this->response->abort(404);

        }

        $this->view(

            'admin/reviews/show',

            [

                'pageTitle' => 'Review Details',

                'review' => $review

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approve Review
    |--------------------------------------------------------------------------
    */

    public function approve(
        int $id
    ): void
    {
        $this->reviewModel
            ->approve($id);

        setFlash(

            'success',

            'Review approved successfully.'

        );

        $this->redirect(

            BASE_URL .
            '/admin/reviews'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Review
    |--------------------------------------------------------------------------
    */

    public function reject(
        int $id
    ): void
    {
        $this->reviewModel
            ->reject($id);

        setFlash(

            'success',

            'Review rejected successfully.'

        );

        $this->redirect(

            BASE_URL .
            '/admin/reviews'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reply
    |--------------------------------------------------------------------------
    */

    public function reply(
        int $id
    ): void
    {
        $review = $this->reviewModel
            ->find($id);

        if (!$review) {

            $this->response->abort(404);

        }

        if (

            $this->request->isPost()

        ) {

            $reply = trim(

                $this->request->input(

                    'reply'

                )

            );

            $this->reviewModel
                ->reply(

                    $id,

                    $reply

                );

            setFlash(

                'success',

                'Reply submitted successfully.'

            );

            $this->redirect(

                BASE_URL .
                '/admin/reviews/' .
                $id

            );

        }

        $this->view(

            'admin/reviews/reply',

            [

                'pageTitle' => 'Reply Review',

                'review' => $review

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Review
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): void
    {
        $this->reviewModel
            ->delete($id);

        setFlash(

            'success',

            'Review deleted successfully.'

        );

        $this->redirect(

            BASE_URL .
            '/admin/reviews'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): void
    {
        $this->response->json(

            $this->reviewModel
                ->statistics()

        );
    }
}