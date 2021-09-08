

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


include('https://code.jquery.com/jquery-3.5.1.slim.min.js', function() {
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
$('#mosmos').addClass('alert alert-warning');
$('#mosmos').attr("data-target", "#mosmosmodal");
$('#mosmos').attr("data-toggle",'modal');

var s=`
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="mosmosmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
`;

$('body').append(s);
// this is the id of the form
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
              $("#responsedivmosmos").html("<p><strong> "+data.message+"</strong>.</p>"+
                '<img src="https://t3.ftcdn.net/jpg/03/55/05/12/360_F_355051279_iCFYD4WdBAfkLljJQlyWYDgii03rinlH.jpg" style="width: 100px;height: 100px;margin-left: auto;margin-right: auto;display: block;" style="">');
                      console.log(data);

            }
            else{
              //onDeclinefunc(data);
          
                   $("#responsedivmosmos").html("<p> "+data.message+"</p> <br>"+
                '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4cHYs5t8hc25yl3SAA2caI5JDWezWPKligQ&usqp=CAU" style="width: 100px;height: 100px;margin-left: auto;margin-right: auto;display: block;" style="">');
                      console.log(data);

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





