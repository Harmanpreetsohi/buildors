<div class="quote_wrapper">
    <div class="page_content_container">
        <div class="page_content_outer">
            <h6>Page content</h6>
            <h5>Property Overview</h5>
        </div>
        <div class="btn_outer">
            <button class="btn btn-primary ">Page Settings</button>
            <button class="btn btn-primary ">View Page</button>
        </div>
    </div>
    <!-- new quote -->
    <div class="addQuote_wrapper">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="Quote1-tab" data-bs-toggle="tab" data-bs-target="#Quote1" type="button"
                    role="tab" aria-controls="Quote1" aria-selected="true">
                    <i class="fa fa-bars" aria-hidden="true"></i>Silicone Coating
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link btn btn-primary" id="Quote2-tab" data-bs-toggle="tab" data-bs-target="#Quote2"
                    type="button" role="tab" aria-controls="Quote2" aria-selected="false">
                    <i class="fa fa-bars" aria-hidden="true"></i>New quote
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </li>

        </ul>

    </div>
    <!-- tab `1 -->
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="Quote1" role="tabpanel" aria-labelledby="Quote1-tab">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class=" col-lg-4 col-md-6 col-sm-12">
                            <label>Include all items from</label>
                            <select class="form-select">
                                <option>1</option>
                                <option>1</option>
                            </select>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <label>Description</label>
                            <textarea class="w-100"> </textarea>
                        </div>

                    </div>
                </div>
                
            </div>
            
        </div>
        <!--  tab 2 -->
        <div class="tab-pane fade " id="Quote2" role="tabpanel" aria-labelledby="Quote2-tab">
        <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class=" col-lg-4 col-md-6 col-sm-12">
                            <label>Include all items from</label>
                            <select class="form-select">
                                <option>1</option>
                                <option>1</option>
                            </select>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <label>Description</label>
                            <textarea class="w-100"> </textarea>
                        </div>

                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- section -->
    <div class="quote_section">
        <div class="card">
            <div class="card-body">
                <!-- quote section -->
                <div class="Quote_section_innerContent">
                    <div class="row">
                        <div class=" col-xl-9 col-lg-8 col-sm-12 col-md-6 ">
                            <label> Section title <i class="fa fa-caret-down" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" />
                        </div>
                        <div class=" col-xl-3 col-lg-4 col-sm-12 col-md-6 ">
                            <div class="quote_switch_outer">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault">
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- item -->
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="item_outer">
                                <label>Item</label>
                                <div class="item_content">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                    <div class="textArea_outer">
                                        <textarea col="100" row="20" class="textArea"></textarea>
                                        <i class="fa fa-plus plus_icon" aria-hidden="true" data-bs-toggle="modal"
                                            data-bs-target="#add_item"></i>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 ">
                            <label>Quantity</label>
                            <input class="form-control" type="number" />
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 ">
                            <label>Price</label>
                            <input class="form-control" type="number" />
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 ">
                            <label>Line total</label>

                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="section_total_btn_outer">
                        <button class="btn btn-primary">
                            Add item
                        </button>
                        <h5>
                            section total: $0.0
                        </h5>
                    </div>
                </div>
                <!-- another qoute section -->
                <div class="Quote_section_innerContent">
                    <div class="row">
                        <div class=" col-xl-9 col-lg-8 col-sm-12 col-md-6 ">
                            <label> Section title <i class="fa fa-caret-down" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" />
                        </div>
                        <div class=" col-xl-3 col-lg-4 col-sm-12 col-md-6 ">
                            <div class="quote_switch_outer">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault">
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- item -->
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="item_outer">
                                <label>Item</label>
                                <div class="item_content">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                    <div class="textArea_outer">
                                        <textarea col="100" row="20" class="textArea"></textarea>
                                        <i class="fa fa-plus plus_icon" aria-hidden="true"></i>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 ">
                            <label>Quantity</label>
                            <input class="form-control" type="number" />
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 ">
                            <label>Price</label>
                            <input class="form-control" type="number" />
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 ">
                            <label>Line total</label>

                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="section_total_btn_outer">
                        <button class="btn btn-primary">
                            Add item
                        </button>
                        <h5>
                            section total: $0.0
                        </h5>
                    </div>
                </div>
                <div class="quote_add_section_btn">
                    <button class="btn btn-primary">Add section</button>

                </div>
                <div class="row">

                    <div class="col-lg-6 col-md-12 col-sm-12 ms-auto">
                        <div class="total_outer">
                            <h6>
                                Quote subtotal</h6>


                            <h5 class=" mt-2">
                                Total</h5>

                        </div>
                    </div>
                </div>
                <div class="notes_wrapper">
                    <h5 class="pt-3">Notes</h5>
                    <textarea class="text_area"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- quote add item modal -->
<!-- Modal -->
<div class="modal fade" id="add_item" tabindex="-1" aria-labelledby="add_itemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_itemLabel">Add Price Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div>
                        <label>Name</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div>
                        <label>Description</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div>
                        <label>Unit</label>
                        <select class="form-select">
                            <option>1</option>
                            <option>2</option>
                        </select>
                    </div>
                    <div>
                        <label>Price adjustment</label>
                        <div class="price_outer">
                            <div>
                                <label>Material</label>
                                <input type="number" class="form-control" />
                            </div>
                            <div>
                                <label>Labor</label>
                                <input type="number" class="form-control" />
                            </div>
                            <div>
                                <label>
                                    Markup</label>
                                <input type="number" class="form-control" />
                            </div>
                            <div>
                                <label>Price</label>
                                <input type="number" class="form-control" />
                            </div>

                        </div>
                    </div>
                    <div class="form-check tex_checkbox">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            This item is tax exempt
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-primary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>