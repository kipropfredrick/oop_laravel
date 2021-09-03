

var scripts = document.getElementsByTagName('script');
var lastScript = scripts[scripts.length-1];
var scriptName = lastScript.src;
//alert("loading: " + lastScript.getAttribute('one'));
var url_string = scriptName; 
var url = new URL(url_string);
var secret_key = url.searchParams.get("secret_key");



var image="";
var itemName=""
var onApprovefunc= function(data){
  
};
var onDeclinefunc=function(data){
};
var price=0;
// Create new link Element
        var link = document.createElement('link'); 
  
        // set the attributes for link element
           link.rel = 'stylesheet'; 
      
        link.type = 'text/css';
      
        link.href = 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'; 
  
        // Get HTML head element to append 
        // link element to it 
        document.getElementsByTagName('HEAD')[0].appendChild(link); 


include('https://code.jquery.com/jquery-3.6.0.js', function() {
    $(document).ready(function() {


//add other scripts



        include('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', function() {
    $(document).ready(function() {
 // add bootstrap script 


  include('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', function() {
    $(document).ready(function() {
          //const mosmosobj = new mosmos("Lipa Mos Mos");

$('#mosmos').addClass('alert alert-warning');
$('#mosmos').attr("data-target", "#mosmosmodal");
$('#mosmos').attr("data-toggle",'modal');

var s=`
<div class="modal fade" id="mosmosmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">




      <div class="modal-header text-center">
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="site__body">
        
           
        <div style="margin-top:20px" class="checkout block">
            <div class="container">
                <div class="row">
                                                <!-- features -->

            <div class="col-sm-4">

                    <div class="mdgf">
                        <div class="row" >
                        <img src="not set" id="mosmosproductimage" class="col-12">
                        </div>
                    </div>
              
      
    </div>
                    <div class="col-12 col-lg-6 col-xl-7">
                        <div class="card mb-lg-0">
                            <div class="card-body">

                                <div class="">
                                <div>
                               <p>You are making a booking for <strong><span id="productName"></span></strong>. Minimum deposit is <strong>KSh.500</strong>.</p>
                         
                            </div>
                                      
                                </div>
                                
                                    <hr>
                                    <h5>Personal Details</h5>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                    <form action="https://mosmos.co.ke/api/smartchekout" method="post" id="mosmos-form">
                                       
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="product_id" value="1">
                                        <input name="status" value="pending" type="hidden">
                                        <input name="minDeposit" value="1" type="hidden">
                                        <div class="form-row">
<div class="form-group col-md-6">
                                        <input name="vendor_code" value="xx" hidden="">
                                        <label for="checkout-first-name">Full name</label><span style="color:red">*</span>
                                        <input required name="name" type="text" class="form-control" id="checkout-first-name" value="" placeholder="Full Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="checkout-last-name">Email</label><span style="color:red">*</span>
                                            <input required type="email" value="" name="email" type="text" class="form-control" id="checkout-last-name" placeholder="Email Address">
                                            </div>
                                        </div>
                                          <div class="form-row">
                                          <div class="form-group col-md-6">
                                                <label for="checkout-company-name">Phone Number<span style="color:red">*</span>
                                                </label>
                                                <input required name="phone" type="" value="" class="form-control" id="checkout-company-name" placeholder="07XXXXXXXX">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="checkout-street-address">Initial Deposit <span style="color:red">*</span> </label>
                                                <input min="500" required name="initial_deposit" value="" type="number" class="form-control" id="checkout-street-address" placeholder="KSh.500 minimum">
                                             </div>
                                          </div>


                                          <h5>Delivery Details</h5>

                                         
                                          <div  id="location-fields" class="location-fields">

                                          <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="checkout-company-name">County</label><span style="color:red">*</span>
                                            
                                              <select id="counties" class="form-control js-example-basic-single" name="county_id" placeholder="Enter name" type="text" class="form-control "  required>
                                                <option value="">Select/search county</option>
                                               <option value="1">Mombasa</option>
                                                </select>
                                           
                                                </div>
                                            
                                                <div class="form-group col-md-6">
                                                <label for="checkout-street-address">Exact Location</label><span style="color:red">*</span>
                                                
                                                <input min="100" required name="exact_location" value="" type="" class="form-control" id="checkout-street-address" placeholder="Eg. City, Town, street name">

                                                <div class="col-lg-10">
                                                   
                                            </div>
                                             </div>
                                          </div>


                                          </div>

                                             <!-- terms -->
                                            <div class="mb-2">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" checked id="terms" required>
                                                    <label class="form-check-label" for="terms">
                                                        I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy-policy" target="_blank">Privacy Policy</a>.*
                                                    </label>
                                                    </div>
                                                </div>
                                            </div>
                                                <button type="submit" class="btn btn-primary">Make Booking</button>
                                          



      <div class="col-12 overlay" style="position:absolute;display:none;" id="spinner">
      <div class="d-flex justify-content-center">  
      

        <span class="fa fa-spinner fa-spin fa-4x " style="color: orange;"></span></div>
      </div>
    </div>

 <style type="text/css">
  .overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 1000;
    top: 40%;
    left: 0px;
    opacity: 1.0;
    filter: alpha(opacity=50);
 }

 </style>


                                            </form>
                                              </div>
                                            <div class="card-divider"></div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

        
  </div>
</div>

<style>
.mdg-features {
    background: #333;
    color: #ffffff;
    padding: 20px;
    margin: 0;
    border-bottom: 5px solid #f68b1e;
}
  </style>
`;

$('body').append(s);
// this is the id of the form
$("#mosmos-form").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
$("#spinner").show();
    var form = $(this);

var productName='<input type="hidden" name="name" value="'+itemName+'">';
var keyInput='<input type="hidden" name="secret_key" value="'+secret_key+'">';
var productPrice='<input type="hidden" name="productPrice" value="'+price+'">';

// var productPrice='<input type="hidden" name="name" value="'+productPrice+'">';
// var productPrice='<input type="hidden" name="name" value="'+productPrice+'">';
form.append(productName);
form.append(keyInput);
form.append(productPrice);
    var url = form.attr('action');
    
    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
            $("#spinner").hide();
            if(data.status){
              $("#mosmosmodal").modal('hide');
              onApprovefunc(data);
                      console.log(data);

            }
            else{
              onDeclinefunc(data);
                      console.log(data);
            }
              // alert(JSON.stringify(data)); // show response from the php script.
           },
           error:function(data){
            console.log(data);
            alert("An error occured processing your request");
            $("#spinner").hide();
           }
         });

    
});

      document.getElementById("mosmosproductimage").src =image;
       document.getElementById("productName").innerHTML =itemName;
    });
}); 





    });
}); 





    });
});


//
include('https://kit.fontawesome.com/a076d05399.js', function() {
 }); 




function include(filename, onload) {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.src = filename;
    script.type = 'text/javascript';
    script.onload = script.onreadystatechange = function() {
        if (script.readyState) {
            if (script.readyState === 'complete' || script.readyState === 'loaded') {
                script.onreadystatechange = null;                                                  
                onload();
            }
        } 
        else {
            onload();          
        }
    };
    head.appendChild(script);
}


class mosmos {
  constructor( {name="Lipa Mosmos",productName="product name not set correctly",
    imageSource="not set",productPrice=0,onApprove=function(data){alert("sasa")}, onDecline=function(data){}} = {}) {
    this.name = name;
    document.getElementById("mosmos").innerHTML =name;
    image=imageSource;
    itemName=productName;
    onApprovefunc=onApprove;
    onDeclinefunc=onDecline;
    price=productPrice;

  }

}





