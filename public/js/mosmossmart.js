

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
var weight=0;
// Create new link Element
        var link = document.createElement('link'); 
  
        // set the attributes for link element
           link.rel = 'stylesheet'; 
      
        link.type = 'text/css';
      
        link.href = 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css'; 
  
        // Get HTML head element to append 
        // link element to it 
        document.getElementsByTagName('HEAD')[0].appendChild(link); 



            var link1 = document.createElement('link'); 
  
        // set the attributes for link element
           link1.rel = 'stylesheet'; 
      
        link1.type = 'text/css';
      
        link1.href = 'https://mosmos.co.ke/css/api.css'; 
  
        // Get HTML head element to append 
        // link element to it 
        document.getElementsByTagName('HEAD')[0].appendChild(link1); 


            var link2 = document.createElement('link'); 
  
        // set the attributes for link element
           link2.rel = 'preconnect'; 
      
   
      
        link2.href = 'https://fonts.gstatic.com'; 
  
        // Get HTML head element to append 
        // link element to it 
        document.getElementsByTagName('HEAD')[0].appendChild(link2); 




            var link3 = document.createElement('link'); 
  
        // set the attributes for link element
           link3.rel = 'stylesheet'; 
      
        link3.type = 'text/css';
      
        link3.href = 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap'; 
  
        // Get HTML head element to append 
        // link element to it 
        document.getElementsByTagName('HEAD')[0].appendChild(link3);


include('https://code.jquery.com/jquery-3.5.1.min.js', function() {
    $(document).ready(function() {


//add other scripts



 //        include('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', function() {
 //    $(document).ready(function() {
 // // add bootstrap script 


 //  include('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', function() {
 //    $(document).ready(function() {
 //          //const mosmosobj = new mosmos("Lipa Mos Mos");

  include('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', function() {
    $(document).ready(function() {
 //          //const mosmosobj = new mosmos("Lipa Mos Mos");
$('#mosmos').addClass('btn p-btn');
$('#mosmos').attr("data-target", "#exampleModal");
$('#mosmos').attr("data-toggle",'modal');

var s=`
 <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Order now, Lipa Mos Mos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                    <div id="successdiv" class="bg-success text-white">

                    </div> 
                       <div id="failurediv" class="bg-danger text-white ">

                    </div>

                     <div class="co-intro" id="desc">
                            You are booking <strong><span id="productName"></span></strong> for <strong>KSh. <span id="productPrice"></span></strong>. Pay conveniently in flexible installments within 90 days at 0% interest.
                            Minimum deposit is <strong>KSh.500</strong>.
                        </div>


                       <form action="https://mosmos.co.ke/api/smartchekout" method="post" id="mosmos-form">
                                       
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="product_id" value="1">
                                        <input name="status" value="pending" type="hidden">
                                        <input name="minDeposit" value="1" type="hidden">
                        
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Full Name*</label>
                                    <input required="" name="name" type="text" class="form-control" placeholder="Full Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone Number*</label>
                                    <input  type="tel" required name="phone" class="form-control" placeholder="Phone Number">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Email Address*</label>
                                    <!-- make it required for now -->
                                    <input required="" type="email" name="email" class="form-control" placeholder="Email Address">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Initial Deposit*</label>
                                    <input type="number" min="500" required name="initial_deposit" class="form-control" placeholder="Ksh.500 minimum">
                                </div>
                            </div>

                            <!-- outside nairobi (select2) -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Select your county*</label>
                                    <select id="county" class="form-control" name="county_id">
                                        <option selected>-- Select your county --</option>
                                         <option value="2" class="counties">Mombasa</option>
                                                                                    <option value="3" class="counties">Kwale</option>
                                                                                    <option value="4" class="counties">Kilifi</option>
                                                                                    <option value="5" class="counties">Tana River</option>
                                                                                    <option value="6" class="counties">Lamu</option>
                                                                                    <option value="7" class="counties">Taita–Taveta</option>
                                                                                    <option value="8" class="counties">Garissa</option>
                                                                                    <option value="9" class="counties">Wajir</option>
                                                                                    <option value="10" class="counties">Mandera</option>
                                                                                    <option value="11" class="counties">Marsabit</option>
                                                                                    <option value="12" class="counties">Isiolo</option>
                                                                                    <option value="13" class="counties">Meru</option>
                                                                                    <option value="14" class="counties">Tharaka-Nithi</option>
                                                                                    <option value="15" class="counties">Embu</option>
                                                                                    <option value="16" class="counties">Kitui</option>
                                                                                    <option value="17" class="counties">Machakos</option>
                                                                                    <option value="18" class="counties">Makueni</option>
                                                                                    <option value="19" class="counties">Nyandarua</option>
                                                                                    <option value="20" class="counties">Nyeri</option>
                                                                                    <option value="21" class="counties">Kirinyaga</option>
                                                                                    <option value="22" class="counties">Murang&#039;a</option>
                                                                                    <option value="23" class="counties">Kiambu</option>
                                                                                    <option value="24" class="counties">Turkana</option>
                                                                                    <option value="25" class="counties">West Pokot</option>
                                                                                    <option value="26" class="counties">Samburu</option>
                                                                                    <option value="27" class="counties">Trans-Nzoia</option>
                                                                                    <option value="28" class="counties">Uasin Gishu</option>
                                                                                    <option value="29" class="counties">Elgeyo-Marakwet</option>
                                                                                    <option value="30" class="counties">Nandi</option>
                                                                                    <option value="31" class="counties">Baringo</option>
                                                                                    <option value="32" class="counties">Laikipia</option>
                                                                                    <option value="33" class="counties">Nakuru</option>
                                                                                    <option value="34" class="counties">Narok</option>
                                                                                    <option value="35" class="counties">Kajiado</option>
                                                                                    <option value="36" class="counties">Kericho</option>
                                                                                    <option value="37" class="counties">Bomet</option>
                                                                                    <option value="38" class="counties">Kakamega</option>
                                                                                    <option value="39" class="counties">Vihiga</option>
                                                                                    <option value="40" class="counties">Bungoma</option>
                                                                                    <option value="41" class="counties">Busia</option>
                                                                                    <option value="42" class="counties">Siaya</option>
                                                                                    <option value="43" class="counties">Kisumu</option>
                                                                                    <option value="44" class="counties">Homa Bay</option>
                                                                                    <option value="45" class="counties">Migori</option>
                                                                                    <option value="46" class="counties">Kisii</option>
                                                                                    <option value="47" class="counties">Nyamira</option>
                                                                                    <option value="48" class="counties">Nairobi</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Exact Location*</label>
                                    <input type="text" required name="exact_location" class="form-control" placeholder="E.g. Town, street">
                                </div>
                            </div>

                            <!-- terms -->
                            <div class="mb-2">
                                <div class="form-group">
                                    <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms">
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="https://mosmos.co.ke/terms" target="_blank">Terms of Service</a> and <a href="https://mosmos.co.ke/privacy-policy" target="_blank">Privacy Policy</a>*
                                    </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-block p-btn">Complete Booking</button>
                        
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

  <div class="powered">
                    <small></span><a href="https://mosmos.co.ke/" target="_blank">Powered by Mosmos Payments</a></small>
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
.modal-container { z-index: 9999999}
  </style>
`;

$('body').append(s);



// this is the id of the form
    // var checkeventcount = 1,prevTarget;
    // $('.modal').on('show.bs.modal', function (e) {
    //     if(typeof prevTarget == 'undefined' || (checkeventcount==1 && e.target!=prevTarget))
    //     {  
    //       prevTarget = e.target;
    //       checkeventcount++;
    //       e.preventDefault();
    //       $(e.target).appendTo('body').modal('show');
    //     }
    //     else if(e.target==prevTarget && checkeventcount==2)
    //     {
    //       checkeventcount--;
    //     }
    //  });

$("#mosmos-form").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
$("#spinner").show();
    var form = $(this);

var productName='<input type="hidden" name="product_name" value="'+itemName+'">';
var keyInput='<input type="hidden" name="secret_key" value="'+secret_key+'">';
var productPrice='<input type="hidden" name="productPrice" value="'+price+'">';
var weight='<input type="hidden" name="weight" value="'+weight+'">';

// var productPrice='<input type="hidden" name="name" value="'+productPrice+'">';
// var productPrice='<input type="hidden" name="name" value="'+productPrice+'">';
form.append(productName);

form.append(keyInput);
form.append(productPrice);
form.append(weight);
    var url = form.attr('action');
    
    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
            $("#spinner").hide();
            if(data.status){
              // $("#mosmosmodal").modal('hide');
              //onApprovefunc(data);
              $("#desc").html("<h3>Booking successful</h3><p>Activate your order by paying <strong>KSh.500</strong> to Paybill Number <strong>4040299</strong> and Account Number <strong>"+data.booking_reference+"</strong>. Thank you.</p>");
                      console.log(data);
                      $("#mosmos-form").hide();
   $("#failurediv").html("<p> "+""+"</p>");
            }
            else{
              //onDeclinefunc(data);
          
                   $("#failurediv").html("<p class='p-1'> "+data.message+"</p>");
                      console.log(data);
$("#successdiv").html("<p > "+""+"</p>");
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

      // document.getElementById("mosmosproductimage").src =image;
       document.getElementById("productName").innerHTML =itemName;
        document.getElementById("productPrice").innerHTML =price;
       
//     });
// }); 





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
  constructor( {productweight=0,name="Lipa mosmos",productName="product name not set correctly",
    imageSource="not set",productPrice=0,onApprove=function(data){alert("sasa")}, onDecline=function(data){}} = {}) {
    this.name = name;
    document.getElementById("mosmos").innerHTML =name;
    image=imageSource;
    itemName=productName;
    onApprovefunc=onApprove;
    onDeclinefunc=onDecline;
    price=productPrice;
    weight=productweight;

  }

}





