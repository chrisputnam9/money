<div id='tab_2'>

        <div class='col-sm-12'>  
            <div class="form-group">
                <label>Image</label>
                <input type='hidden' name='image' value='{{image}}'/>
                <br/>
                <div class='window window--thumbnail img-thumbnail'>
                    <img class='img-responsive img-stretch' src='/upload/transaction/{{image}}' alt='{{image}}'/>
                </div>
                <div class='js-show'>
                    <small class='float--right'><a href='#ocr-text' class='js-toggle'>View Raw OCR Text</a></small>
                    <div class='float--right full-width'>
                        <pre id='ocr-text'>{{ocr-text}}</pre>
                        <br/>
                    </div>
                </div>
            </div>
        </div>

</div>
