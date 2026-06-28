<div
    class="modal fade"
    id="productModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h4
                    class="modal-title"
                    id="productTitle">

                    Product

                </h4>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal">

                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-lg-5">

                        <img
                            id="productImage"
                            src=""
                            class="img-fluid rounded">

                    </div>

                    <div class="col-lg-7">

                        <div class="mb-4">

                            <label class="form-label">

                                Size

                            </label>

                            <div id="variantContainer">

                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Sugar Level

                            </label>

                            <div id="sugarContainer">

                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Ice Level

                            </label>

                            <div id="iceContainer">

                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Extras

                            </label>

                            <div id="extraContainer">

                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Quantity

                            </label>

                            <input
                                type="number"
                                id="productQty"
                                value="1"
                                min="1"
                                class="form-control">

                        </div>

                        <div>

                            <label class="form-label">

                                Notes

                            </label>

                            <textarea
                                id="productNotes"
                                rows="3"
                                class="form-control"
                                placeholder="Special instructions..."></textarea>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <div class="me-auto">

                    <h4>

                        Total :

                        <span id="modalTotal">

                            Rs. 0

                        </span>

                    </h4>

                </div>

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    id="addToCartModal"
                    class="btn btn-success">

                    Add To Cart

                </button>

            </div>

        </div>

    </div>

</div>