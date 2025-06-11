<style>
.spinner {
  display: inline-block;
  width: 24px;
  height: 24px;
  border: 3px solid rgb(17, 152, 235);
  border-top-color: rgb(9, 229, 171);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}
.chat-body {
  background-color: #f9f9f9;
  padding: 10px;
  border-radius: 10px;
}

/* Base chat message style */
.answer {
  display: flex;
  flex-direction: column;
  margin: 10px 0;
  max-width: 100%;
  word-wrap: break-word;
}

/* Left (bot) messages */
.answer.left {
  align-self: flex-start;
  background-color: #e4e6eb;
  border-radius: 15px 15px 15px 0;
  padding: 10px;
  color: #000;
}

/* Right (user) messages */
.answer.right {
  align-self: flex-end;
  background-color: #0de0fe;
  border-radius: 15px 15px 0 15px;
  padding: 10px;
  color: #000;
}

/* Message text */
.answer .text {
  font-size: 14px;
}

/* Timestamp */
.answer .time {
  text-align: right;
  font-size: 10px;
  margin-top: 5px;
  color: #666;
}
</style>


<button class="open-button btn btn-primary" onclick="openForm()">AI <font size="4"><b>ডাক্তার স্বপ্নার সাথে কথা বলুন</b></font></button>

<div class="chat-popup" id="myForm" style="border-radius: 10px;">
  <div >

<font>
   <form class="form-container" id="chatForm" enctype="multipart/form-data" >
   <h3>ডাক্তার স্বপ্নার সাথে কথা বলুন</h3>
   <hr> 
   <div class="chat-body" id="chatBox" style="height: 450px; overflow-y: auto;">
   </div>

  <input type="text" id="messageInput" placeholder="বার্তা লিখুন..." required class="form-control my-1">

   <button type="submit" class="btn btn-block btn-primary rounded">
      বার্তা পাঠান
   </button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function appendMessage(text, side = 'left', tempId = null) {
    let time = new Date().toISOString();
    let messageHtml = `
        <div class="answer ${side}" ${tempId ? `id="${tempId}"` : ''}>
            <div class="text"><font size="4px">${text}</font></div>
            <div class="time"><font size="1px"></font></div>
        </div>
    `;
    $('#chatBox').append(messageHtml);
    $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
}

function typeTextEffect(elementId, prefix, fullText, speed = 30) {
    let index = 0;
    const target = $(`#${elementId} .text`);
    function typeChar() {
        if (index <= fullText.length) {
            target.html(`<font size="4px">${prefix}${fullText.substring(0, index)}</font>`);
            index++;
            setTimeout(typeChar, speed);
        }
    }
    typeChar();
}

$('#chatForm').on('submit', function(e) {
    e.preventDefault();
    const message = $('#messageInput').val().trim();
    if (message === '') return;

    appendMessage('<b>রোগী:</b>' + message, 'right');
    $('#messageInput').val('');

    const tempId = 'thinkingMessage';
    appendMessage('<div class="spinner"></div> ডাক্তার স্বপ্না ভাবছে...', 'left', tempId);

    $.ajax({
        url: '/ai-assistant-chat',
        type: 'POST',
        data: { message },
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(res) {
            $(`#${tempId}`).remove();

            const finalId = `reply-${Date.now()}`;
            appendMessage('', 'left', finalId); 
            const replyText = res.reply || 'ডাক্তার স্বপ্না উত্তর দিতে ব্যর্থ হয়েছে';
            typeTextEffect(finalId, '<b>ডাক্তার স্বপ্না:</b> ', replyText);
        },
        error: function() {
            $(`#${tempId}`).remove();
            appendMessage('সার্ভারে সমস্যা হয়েছে। পরে আবার চেষ্টা করুন।', 'left');
        }
    });
});

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }

    function loadChat() {
        $('#chatBox').html('');
    }

    
</script>
</font>


    <center>
    <button type="button" class="btn cancel book-btn" onclick="closeForm()" style="position: relative;width:95%"><font size="4"><b>বন্ধ করুন</b></font></button>
    </center>
    </div>
</div>
