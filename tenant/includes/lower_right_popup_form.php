<button class="open-button" onclick="openForm()">
  <i class="fa fa-comments"></i>
</button>
<div class="form-popup shadow" id="helpForm">
    <div class="form-container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h3 class="text-light" style="font-weight: bold;">Hi, welcome to</h3>
          <a href="dashboard.php" class="brand-link">
            <span class="brand-text font-weight-light"><b class="p-2" style="background-color:#FFC107; border:2px solid #FFC107; border-top-left-radius:5px; font-weight:bold; color:#00192D;">BT</b><b class="p-2" style=" border-bottom-right-radius:5px; font-weight:bold; border:2px solid #FFC107; color: #FFC107;">JENGOPAY</b></span>
          </a>
          <p class="text-center text-light">Support Center. Our Team is Here to help you with any questions you may have.</p>
        </div>
      </div>
      <!-- Accordions to Show Quick Solutions to the Users -->
      <div class="row mb-3 mt-3" style="background-color:#fff; border-radius: 7px;">
        <div class="col-md-12">
          <p class="text-dark text-center mt-2" style="font-weight:bold;">Get Quick Guide about BT-JengoPay</p>
          <div id="accordion">
            <!-- First Collapsible Accordion -->
            <div class="card shadow">
              <div class="card-header" style="background:linear-gradient(#00192D, 80%, rgb(0, 25, 45, .9));">
                <p class="card-title w-100">
                  <a class="d-block w-100" data-toggle="collapse" href="#qSolutionOne" style="color: #fff; font-size: 16px !important;">
                    Getting Started with BT-JengoPay?
                  </a>
                </p>
              </div>
              <div id="qSolutionOne" class="collapse" data-parent="#accordion">
                <div class="card-body">
                  <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin.</p>
                </div>
              </div>
            </div>
            <!-- Second Collapsible Accordion -->
            <div class="card shadow">
              <div class="card-header" style="background:linear-gradient(#00192D, 80%, rgb(0, 25, 45, .9));">
                <p class="card-title w-100">
                  <a class="d-block w-100" data-toggle="collapse" href="#qSolutionTwo" style="color: #fff; font-size: 16px !important;">
                    How to Know Payment Status?
                  </a>
                </p>
              </div>
              <div id="qSolutionTwo" class="collapse" data-parent="#accordion">
                <div class="card-body">
                  <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin.</p>
                </div>
              </div>
            </div>
            <!-- Third Collapsible Accordion -->
            <div class="card shadow">
              <div class="card-header" style="background:linear-gradient(#00192D, 80%, rgb(0, 25, 45, .9));">
                <p class="card-title w-100">
                  <a class="d-block w-100" data-toggle="collapse" href="#qSolutionThree" style="color: #fff; font-size: 16px !important;">
                    How is Data Privacy on BT-JengoPay?
                  </a>
                </p>
              </div>
              <div id="qSolutionThree" class="collapse" data-parent="#accordion">
                <div class="card-body">
                  <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
        <div class="row mb-3 mt-3">
            <div class="col-md-12">
                <!-- Live Chat Section -->
            <div class="chatbot-container shadow mb-3 bg-light">
                <div id="header">
                    Let's Talk
                </div>
                <div id="chatbot">
                    <div id="conversation">
                      <div class="chatbot-message">
                        <p class="chatbot-text">Hi, I'm Pashan👋 it's great to see you! What w'd you like to ask?</p>
                      </div>
                    </div>
                    <form id="input-form">
                      <message-container>
                          <div class="row">
                              <div class="col-md-10">
                                  <input type="text" class="form-control mt-2 shadow" name="search_msg" placeholder="Type your Message Here..." required style="height:40px !important; border-radius: 0 !important;" data-toggle="tooltip" title="Type your Message Here and We'll get back to you" id="input-field" >
                              </div>
                              <div class="col-md-2">
                                  <button id="submit-button" class="btn p-2 mt-2" >Submit</button>
                              </div>
                          </div>
                    </message-container>

                    </form>
                </div>
            </div>
            </div>
        </div>
      
      <div class="container">
        <button type="button" class="btn cancel" onclick="closeForm()" style="background-color:#cc0001;">Close</button>
      </div>
    </div>
</div>