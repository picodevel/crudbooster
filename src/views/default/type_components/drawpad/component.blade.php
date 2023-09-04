
<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }} {{$col_group_width?:"col-sm-12"}}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    @if($value)
    <label class="control-label col-xs-2" style="{{@$form['label_style']}}">{{$form['label']}} Saved</label>
    <div class="col-xs-12 col-sm-9 no-padding" style="{{@$form['control_style']}}" style="margin-bottom: 15px">
        <img src="{{ asset('storage/'.$value) }}" title="Signature" class="img-responsive" width="240"/>
    </div>
    @endif
    <label class="control-label col-xs-2" style="{{@$form['label_style']}}">{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>
    <div class="col-xs-12 col-sm-9 no-padding" style="{{@$form['control_style']}}" style="margin-bottom: 15px">
        <div id="drawpad-{{$name}}" class="drawpad-wrapper">
            <canvas class="drawpad-canvas"></canvas>
            <div class="drawpad-help">
                {{ @$form['help'] }}
            </div>
            <input name="{{$name}}" type="hidden"/>
        </div>
        <div style="display: flex">
          <button type="button" class="btn btn-xs btn-danger" id="reset_pad_{{$name}}" style="margin-right: 5px">RESET</button>
          {{-- <input type="file" id="choose_file_{{$name}}" accept="image/png" /> --}}

        </div>
        <div class="text-danger">
          {!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}
        </div>
    </div>

</div>

<style>
  #drawpad-{{$name}} {
    overflow-y: hidden !important;
  }
  #drawpad-{{$name}} > .drawpad-canvas {
    border: dashed 2px #D2D6DE;
    background-color: white;
  }
  #drawpad-{{$name}} > canvas {
    width: 100%;
    height: {{@$form['pad_height']}}px;
    max-width: {{@$form['pad_width']}}px;
  }
</style>

<script type="text/javascript">
  var canvas_{{$name}} = document.querySelector("#drawpad-{{$name}} canvas");
  function resizeCanvas_{{$name}}() {
      // When zoomed out to less than 100%, for some very strange reason,
      // some browsers report devicePixelRatio as less than 1
      // and only part of the canvas is cleared then.
      var ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas_{{$name}}.width = canvas_{{$name}}.offsetWidth * ratio;
      canvas_{{$name}}.height = canvas_{{$name}}.offsetHeight * ratio;
      canvas_{{$name}}.getContext("2d").scale(ratio, ratio);
  }
  window.onresize = resizeCanvas_{{$name}};
  resizeCanvas_{{$name}}();
  var signaturePad_{{$name}} = new SignaturePad(canvas_{{$name}}, {
      // backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
    onEnd: function(){
      document.querySelector("input[name='{{$name}}']").value = signaturePad_{{$name}}.toDataURL('image/png');
      /* choose_file_{{$name}}.disabled = true;   */
    }
  });
  /* var choose_file_{{$name}} = document.querySelector("#choose_file_{{$name}}");
  choose_file_{{$name}}.addEventListener('change', function() {
    if (this.files && this.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        signaturePad_{{$name}}.fromDataURL(e.target.result);
      }
      signaturePad_{{$name}}.clear();
      reader.readAsDataURL(this.files[0]);
      signaturePad_{{$name}}.off();
    }
  }); */
  reset_pad_{{$name}}.addEventListener('click', function() {
    signaturePad_{{$name}}.clear();
    document.querySelector("input[name='{{$name}}']").value = "";
    /* choose_file_{{$name}}.value = "";
    choose_file_{{$name}}.disabled = false; */
    signaturePad_{{$name}}.on();
  });
</script>
