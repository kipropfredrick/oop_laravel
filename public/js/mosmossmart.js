

var scripts = document.getElementsByTagName('script');
var lastScript = scripts[scripts.length-1];
var scriptName = lastScript.src;
//alert("loading: " + lastScript.getAttribute('one'));
var url_string = scriptName; 
var url = new URL(url_string);
var secret_key = url.searchParams.get("secret_key");



var mmimage="";
var mmitemName=""
var mmis_in_stock=false;
var mmprice=0;
var mmweight=0;
var onApprovefunc= function(data){
  
};
var onDeclinefunc=function(data){
};

  

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                    var modal = document.getElementById("LMMPopUp");
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
    






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

 //          //const mosmosobj = new mosmos("Lipa Mos Mos");
 var mosmosbutton=document.getElementById("mmBtn");
mosmosbutton.classList.add('btn');
mosmosbutton.classList.add('p-btn');
// $('#mosmos').attr("data-target", "#exampleModal");
// $('#mosmos').attr("data-toggle",'modal');
mosmosbutton.innerHTML='Lipa Mos Mos';
mosmosbutton.onclick=function() {
    if (mmprice!=0) {
if (!mmis_in_stock) {
    alert('This product is out of stock.');
   

}else{
      var modal = document.getElementById("LMMPopUp");
     modal.style.display = "block";
   
}



}else{
        alert('Please select some product options before proceeding with Lipa Mos Mos.');
     
}

      document.getElementById("productName").innerHTML =mmitemName;
        document.getElementById("productPrice").innerHTML =mmprice;
};

var s=`
 




  
            <!-- modal content -->
            <div class="mm-modal-main">
                <div class="mm-modal-content">
                    <div class="mm-modal-header">
                        <span class="mm-modal-title" id="modalTitle">Order now, Lipa Mos Mos</span>
                        <span class="close">&times;</span>
                    </div>

                    <div class="mm-modal-body">
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
                            <div class="mm-form-row">
                                <div class="mm-form-group">
                                    <label>Full Name*</label>
                                             <input required="" id="name" name="name" type="text" placeholder="Full Name">
                                </div>
                                <div class="mm-form-group">
                                    <label>Phone Number*</label>
                                    <input  type="tel" id="phone" required name="phone" placeholder="Phone Number">
                                </div>
                            </div>

                            <div class="mm-form-row">
                                <div class="mm-form-group">
                                    <label>Email Address*</label>
                                    <!-- make it required for now -->
                                  <input required="" id="email" type="email" name="email" placeholder="Email Address">
                                </div>
                                <div class="mm-form-group">
                                    <label>Initial Deposit*</label>
                                        <input id="amount" type="number" min="500" required name="initial_deposit" placeholder="Ksh.500 minimum">
                                </div>
                            </div>

                            <!-- outside nairobi (select2) -->
                            <div class="mm-form-row">
                                <div class="mm-form-group">
                                    <label>Select your county*</label>
                                 <select id="county"  name="county_id">
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

                                <div class="mm-form-group">
                                    <label>Exact Location*</label>
                                    <input id="location" type="text" required name="exact_location"  placeholder="E.g. Town, street">
                                </div>
                            </div>

                            <!-- terms -->
                            <div class="mm-terms">
                                <div>
                                    <input type="checkbox" id="terms">
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="https://mosmos.co.ke/terms" target="_blank">Terms of Service</a> and <a href="https://mosmos.co.ke/privacy-policy" target="_blank">Privacy Policy</a>*
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="p-btn-block p-btn">Complete Booking</button>
                              <div class="col-12 overlay" style="position:absolute;display:none;" id="spinner">
      <div class="row d-flex justify-content-center" style=" display: flex;
  justify-content: center;">  
      

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

                    <div class="powered">
                        <small></span><a href="https://mosmos.co.ke/" target="_blank">Powered by Mosmos Payments</a></small>
                    </div>
                </div>

            </div>
            <!-- end -->
       

        <script>


                </script>
`;
var htmlObject = document.createElement('div');

  htmlObject.setAttribute("id","LMMPopUp");
   htmlObject.setAttribute("class","mm-modal");
htmlObject.innerHTML = s;

 document.body.appendChild(htmlObject);


        var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal 


            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                    var modal = document.getElementById("LMMPopUp");
                modal.style.display = "none";
                }





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

document.getElementById("mosmos-form").addEventListener('submit',function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
document.getElementById("spinner").style.display='block';
    var form = document.getElementById("mosmos-form");

var productName='<input type="hidden" name="product_name" value="'+mmitemName+'">';
var keyInput='<input type="hidden" name="secret_key" value="'+secret_key+'">';
var productPrice='<input type="hidden" name="productPrice" value="'+mmprice+'">';
var weight='<input type="hidden" name="weight" value="'+mmweight+'">';
var allitems=document.createElement("div");
// var productPrice='<input type="hidden" name="name" value="'+productPrice+'">';
// var productPrice='<input type="hidden" name="name" value="'+productPrice+'">';
allitems.innerHTML=productName+keyInput+productPrice+weight;
form.appendChild(allitems);

// form.appendChild(keyInput);
// form.appendChild(productPrice);
// form.appendChild(weight);
    var url = form.action;
    var name=document.getElementById("name").value;
    var email=document.getElementById("email").value;
    var phone=document.getElementById("phone").value;
    var amount=document.getElementById("amount").value;
    var location=document.getElementById("location").value;
    var county=document.getElementById("county").value
    var data = {name:name,email:email,phone:phone,initial_deposit:amount,exact_location:location,county_id:county,product_name:mmitemName,secret_key:secret_key,productPrice:mmprice,weight:mmweight};


var request = new XMLHttpRequest();
               
                request.open("POST", url, true);
                request.setRequestHeader("Content-Type", "application/json");
                request.onreadystatechange = function () {
                    if (request.readyState === 4 && request.status === 200) {
                       var data=JSON.parse(request.responseText);
                        document.getElementById("spinner").style.display='none';
            if(data.status){
              // $("#mosmosmodal").modal('hide');
              //onApprovefunc(data);
              document.getElementById("desc").innerHTML="<h3>Booking successful</h3><p>Activate your order by paying <strong>KSh.500</strong> to Paybill Number <strong>4040299</strong> and Account Number <strong>"+data.booking_reference+"</strong>. Thank you.</p>";
                      console.log(data);
                      document.getElementById("mosmos-form").style.display='none';
   document.getElementById("failurediv").innerHTML="<p> "+""+"</p>";
            }
            else{
              //onDeclinefunc(data);
          
                   document.getElementById("failurediv").innerHTML="<p class='p-1'> "+data.message+"</p>";
                      console.log(data);
document.getElementById("successdiv").innerHTML="<p > "+""+"</p>";
                      console.log(data);
            }
                    }
                };
            
                request.send(JSON.stringify(data));

    
//     $.ajax({
//            type: "POST",
//            url: url,
//            data: form.serialize(), // serializes the form's elements.
//            success: function(data)
//            {
//             $("#spinner").hide();
//             if(data.status){
//               // $("#mosmosmodal").modal('hide');
//               //onApprovefunc(data);
//               $("#desc").html("<h3>Booking successful</h3><p>Activate your order by paying <strong>KSh.500</strong> to Paybill Number <strong>4040299</strong> and Account Number <strong>"+data.booking_reference+"</strong>. Thank you.</p>");
//                       console.log(data);
//                       $("#mosmos-form").hide();
//    $("#failurediv").html("<p> "+""+"</p>");
//             }
//             else{
//               //onDeclinefunc(data);
          
//                    $("#failurediv").html("<p class='p-1'> "+data.message+"</p>");
//                       console.log(data);
// $("#successdiv").html("<p > "+""+"</p>");
//                       console.log(data);
//             }
//               // alert(JSON.stringify(data)); // show response from the php script.
//            },
//            error:function(data){
//             console.log(data);
//             alert("An error occured processing your request");
//             $("#spinner").hide();
//            }
//          });

    
});

      // document.getElementById("mosmosproductimage").src =image;
   
       
//     });
// }); 






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


// class mosmos {
//   constructor( {productweight=0,name="Lipa mosmos",productName="product name not set correctly",
//     imageSource="not set",productPrice=0,onApprove=function(data){alert("sasa")}, onDecline=function(data){}} = {}) {
//     this.name = name;
//     document.getElementById("mosmos").innerHTML =name;
//     image=imageSource;
//     itemName=productName;
//     onApprovefunc=onApprove;
//     onDeclinefunc=onDecline;
//     price=productPrice;
//     weight=productweight;

//   }

// }





