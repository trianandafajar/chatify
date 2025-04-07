<div class="messenger-sendCard">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data">
        @csrf

        <!-- Upload attachment -->
        <label>
            <span class="fas fa-paperclip"></span>
            <input disabled type="file" class="upload-attachment" name="file" accept="image/*, .txt, .rar, .zip" />
        </label>

        <!-- Message box -->
        <textarea readonly name="message" class="m-send app-scroll" placeholder="Type a message.."></textarea>

        <!-- Send button -->
        <button disabled><span class="fas fa-paper-plane"></span></button>
    </form>
</div>
