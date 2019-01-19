<div id='tab_image'>

        <div class='col-sm-12'>  
            <div class="form-group">
                <label>Image</label>
                <input type='hidden' name='image' value='{{image}}'/>
                <br/>
                <div class='window window--thumbnail img-thumbnail'>
                    <img class='img-responsive center-block' src='/upload/transaction/{{image}}' alt='{{image}}'/>
                </div>
                <small class='float--right js-show'><a href='#ocr-text' data-clicktoggle='#ocr-text'>View Raw OCR Text</a></small>
                <div id='ocr-text' class='float--right full-width js-hide'>
                    <pre>{{ocr-text}}</pre>
                    <br/>
                </div>
            </div>
        </div>

</div>
