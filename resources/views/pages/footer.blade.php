<div class="sm:mb-10">
    <h3 class="text-center">Show your Love <span class="heart">&hearts;</span> Support to keep the Website Live...<span class="text-lime-500 ">...</span></h3>
    <p class="text-center">Donate</p>
    <div>

        <div class="flex flex-col md:flex-row justify-around">
            <div class="py-2">
                <p id="copy-message1" class="copy-message text-center">Address Copied!</p>
                <p class="text-center">BTC</p>
                <div>
                    <p class="text-center btc-address" data-copy-target="1E5rDqdyqQEeixV5MYXFtUennntmmVWw5K">
                        1E5rDqdyqQEeixV5MYXFtUennntmmVWw5K
                        <i class="fa-regular fa-copy pl-2" onclick="copyToClipboard(event, 'copy-message1')" title="Copy"></i>
                    </p>
                </div>
            </div>
            <div class="py-2">
                <p id="copy-message2" class="copy-message text-center">Address Copied!</p>
                <p class="text-center">USDT\ETH\Others..</p>
                <p class="text-center usdt-address" data-copy-target="0x0f50b937e8e17b83c6680d374cd26856c5292055">
                    0x0f50b937e8e17b83c6680d374cd26856c5292055
                    <i class="fa-regular fa-copy pl-2" onclick="copyToClipboard(event, 'copy-message2')" title="Copy"></i>
                </p>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function copyToClipboard(event, messageId) {
           const targetElement = event.target.closest('p');
           const textToCopy = targetElement.getAttribute('data-copy-target');

           navigator.clipboard.writeText(textToCopy).then(() => {
               // Show the confirmation message
               const copyMessage = document.getElementById(messageId);
               copyMessage.classList.add('show-message');

               // Hide the message after 2 seconds
               setTimeout(() => {
                   copyMessage.classList.remove('show-message');
               }, 2000);
           }).catch(err => {
               console.error('Failed to copy: ', err);
           });
       }
</script>

