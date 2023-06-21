<div class="col-md-3" style="padding: 0px;">

    <div class="card-body" style="max-height:600px;min-height:700px; overflow-y: auto" id="checkScroll">

        <input type="text" autocomplete="off" class="form-control"
            id="exampleInputIconLeft" placeholder="Search" aria-label="Search" style="margin-bottom: 10px"
            />

        <div id="sideBarContacts">
            <div id="2012109355_container" class="d-flex align-items-center justify-content-between border-bottom py-3">

                <div style="width: 100%">

                    <div class="h6 mb-0 align-items-center">

                        <a href="javascript:void(0)" id="2012109355_chatStarter" onclick="getChats(this,'2012109355')"
                            style="width: 100%;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">2012109355</a>

                        <span id="2012109355_msgTime" style="font-size: 12px;">29 May, 10:30 pm</span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="col-md-9">

    <div class="row justify-content-center" style="min-height: 715px">

        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="chatInfo"
            style="display: none; background: #f2f4f6 !important">

            <div class="container-fluid">

                <div class="collapse navbar-collapse" id="navbarExample01">

                    <div class="col-md-6 showName"></div>

                    <div class="col-md-2"></div>

                    <div class="col-md-1"><img src="assets/img/loading.gif" id="loading" style="display: none"></div>

                    <div class="col-md-3">

                        <input type="text" name="search_in_chat" id="search_in_chat" class="form-control"
                            placeholder="Search in chat" />

                    </div>

                </div>

            </div>

        </nav>

        <div class="col-12" id="chatContainer" style="overflow-y:auto; height: 570px;background-color: beige">

            <div id="welcomeScreen" class="modal-dialog modal-info modal-dialog-centered" role="document"
                style="width: 100%; max-width: 100%; margin: 0px;">

                <div class="modal-content bg-gradient-secondary">

                    <div class="modal-header"></div>

                    <div class="modal-body text-white">

                        <div class="py-3 text-center">

                            <span class="modal-icon">

                                <svg class="icon icon-xl text-gray-200" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z">
                                    </path>
                                    <path
                                        d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z">
                                    </path>
                                </svg>

                            </span>

                            <h2 class="h4 modal-title my-3">Select a Contact!</h2>

                            <p>Please select a contact from left bar and start chat!</p>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <!--<button type="button" class="btn btn-sm btn-white">Go to Inbox</button>-->

                    </div>

                </div>

            </div>

        </div>

        <div class="sendingSection" style="margin-top: 10px;">

            <form action="#" id="chatFrom" class="chatForm" enctype="multipart/form-data" method="post"
                style="display: none">

                <div class="row">

                    <!-- <div class="col-md-3" style="padding: 0px; display: flex">

                        <span data-bs-toggle="modal" data-bs-target="#modal-default"
                            style="cursor: pointer;padding: 0px 10px;font-size: 20px;">&#128512;</span>

                        <i class="fa fa-folder" style="cursor: pointer;font-size: 30px; color: orange"
                            onClick="showUserMedia(this)" data-bs-toggle="modal"
                            data-bs-target="#showUserMedia"></i>&nbsp;&nbsp;

                        <i class="fa fa-sticky-note" data-bs-toggle="modal" data-bs-target="#addNotes"
                            style="cursor: pointer; font-size: 28px; color: green"
                            onClick="getNotes(this)"></i>&nbsp;&nbsp;

                        <i class="fa fa-calendar-check-o" data-bs-toggle="modal" data-bs-target="#todoList"
                            style="cursor: pointer; font-size: 28px; color: blue"
                            onClick="openTodoList(this)"></i>&nbsp;&nbsp;

                        <div class="file-field">

                            <img src="" class="media_preview" style="display: none; width: 30px;" />

                            <div class="d-flex justify-content-center">

                                <div class="d-flex align-items-center">

                                    <svg class="icon icon-md text-gray-400 me-3" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;">
                                        <path fill-rule="evenodd"
                                            d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                            clip-rule="evenodd"></path>
                                    </svg>

                                    <input type="file" name="chat_media" id="chat_media"
                                        style="width: 40px; max-width: 40px;padding-bottom: 0px;"
                                        onChange="showMediaPreview()">

                                </div>

                            </div>

                        </div>

                    </div> -->

                    <div class="col-md-11">

                        <textarea class="form-control shadow mb-4" name="message" id="message"
                            placeholder="Your Message" maxlength="1000" required
                            onKeyPress="OnKeyPress(event)"></textarea>

                    </div>

                    <div class="col-md-1" style="line-height: 55px;">

                        <input type="hidden" name="to_number" id="to_number" value="">

                        <button class="btn btn-icon-only btn-facebook d-inline-flex align-items-center" 
                            onclick="alert('under development');" type="button" >

                            <i class="fa fa-send-o"></i>

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>