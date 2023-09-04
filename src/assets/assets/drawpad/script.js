var canvas = document.getElementById('signature-pad');
// var container = document.getElementById("container");
// var canvas = containerElements[0].querySelectorAll('canvas');
// console.log(containerElements);

// var konva = container.querySelectorAll("div");
// console.log(document.querySelectorAll("canvas"));

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

var signaturePad = new SignaturePad(canvas, {
    // backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
});

document.getElementById('save-png').addEventListener('click', function() {
    if (signaturePad.isEmpty()) {
        if (isCanvasBlank(canvas)) {
            return alert("Please provide a signature first.");
        }
    }

    var data = signaturePad.toDataURL('image/png');

    /*var data = signaturePad.toDataURL('image/png');
    var blob = dataURItoBlob(data);
    var fd = new FormData(document.forms[0]);
    fd.append("tanda_tangan", blob);
    console.log('blob');
    console.log(blob);

    console.log($("#tanda_tangan").val());

    console.log(data);
    window.open(data);*/

    var blobObject = blobCreationFromURL(data);

    // console.log(blobObject);

    var fdataobj = new FormData(); 
    
    // Create formdata object and append the object 
    // file to the name 'Blob file' 
    fdataobj.append("signature", blobObject); 
    

    $.ajax({
       url: "upload.php",
       type: "POST",
       data: {"imageurl" : data},
    }).done(function(respond){
      console.log(respond);
    });

    // $("#tanda_tangan").val(fdataobj);

    // FormData object content is displayed in alert box. 
    // for (var pair of fdataobj.entries()) { 
    //     alert('GeeksforGeeks\n' + pair[0] + '–' + pair[1]) 
    // } 



});

$("#tanda_tangan").change(function() {
    readURL(this);
});

document.getElementById('clear').addEventListener('click', function() {
    signaturePad.clear();
    $("#tanda_tangan").val("");

});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            // $('#blah').attr('src', e.target.result);
            // $('.wrapper').html("<img src='" + e.target.result + "'/>");


            // var canvas = document.getElementById("signature-pad");
            var ctx = canvas.getContext("2d");
            var img = new Image();

            img.onload = function() {
                ctx.drawImage(img, 0, 0, 300, 150);
            }

            img.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function isCanvasBlank(canvas) {
    const context = canvas.getContext('2d');

    const pixelBuffer = new Uint32Array(
        context.getImageData(0, 0, canvas.width, canvas.height).data.buffer
    );

    return !pixelBuffer.some(color => color !== 0);
}

function dataURItoBlob(dataURI) {
    // convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0) {
        byteString = atob(dataURI.split(',')[1]);
    } else {
        byteString = unescape(dataURI.split(',')[1]);
    }

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], {type:mimeString});
}

function blobCreationFromURL(inputURI) { 
  
        var binaryVal; 
  
        // mime extension extraction 
        var inputMIME = inputURI.split(',')[0].split(':')[1].split(';')[0]; 
  
        // Extract remaining part of URL and convert it to binary value 
        if (inputURI.split(',')[0].indexOf('base64') >= 0) 
            binaryVal = atob(inputURI.split(',')[1]); 
  
        // Decoding of base64 encoded string 
        else
            binaryVal = unescape(inputURI.split(',')[1]); 
  
        // Computation of new string in which hexadecimal 
        // escape sequences are replaced by the character  
        // it represents 
  
        // Store the bytes of the string to a typed array 
        var blobArray = []; 
        for (var index = 0; index < binaryVal.length; index++) { 
            blobArray.push(binaryVal.charCodeAt(index)); 
        } 
  
        return new Blob([blobArray], { 
            type: inputMIME 
        }); 
    } 
  
   