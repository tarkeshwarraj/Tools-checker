@include('pages.header')


        {{-- hero section --}}
        <div class="mt-10">
            <h5 class="text-center text-2xl font-bold mb-6">SMTP Checker</h5>

            <div class="flex flex-col items-center">
                <textarea id="smtpDetails" class="w-[90%] md:w-[80%] h-40 border border-gray-300 rounded-md p-2 mb-4 text-black" placeholder="adrien-group.fr|587|userID|password (one per line)"></textarea>
                <button onclick="checkSMTP()" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Check</button>
                <button onclick="stopSMTP()" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Stop</button>
            </div>

            <div class="flex flex-col sm:flex-row justify-evenly md:gap-6 px-4 my-7 sm:my-5">
                <div class="md:mt-10 w-full sm:w-[100%]">
                    <h2 class="text-center pb-2 "><span class="bg-green-400 px-1 rounded-full text-white">Live</span> SMTP</h2>
                    <textarea id="liveSMTP" class="w-full sm:w-[100%] h-40 border border-gray-300 rounded-md p-2 mb-4 resize-none text-black"></textarea>
                </div>
                <div class="md:mt-10 w-full sm:w-[100%]">
                    <h2 class="text-center pb-2"><span class="bg-red-400 px-1 rounded-full text-white">Dead</span>SMTP</h2>
                    <textarea id="deadSMTP" class="w-full sm:w-[100%] h-40 border border-gray-300 rounded-md p-2 mb-4 resize-none text-black"></textarea>
                </div>
            </div>
            
        </div>
        @include('pages.footer')
    


        <script>
            function checkSMTP() {
                const smtpDetails = document.getElementById('smtpDetails').value.trim();
                
                if (!smtpDetails) {
                    alert('Please provide SMTP details.');
                    return;
                }
        
                const formData = new FormData();
                formData.append('smtp_details', smtpDetails);
        
                // Send the details to the Laravel backend
                fetch('/check-smtp', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // document.getElementById('liveSMTP').value = data.live.join('\n');
                        // document.getElementById('deadSMTP').value = data.dead.join('\n');
                        console.log(data.live);
                        console.log(data.dead);
                    } else {
                        alert('Failed to check SMTP. Please try again.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            //Function to stop SMTP checking process
            function stopSMTP() {
                fetch('/smtp-check/stop', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                alert('SMTP checking stopped.');
            } else {
                alert('Failed to stop SMTP checking. Please try again.');
            }
                })
                .catch(error => console.error('Error:', error));
            }
        </script>

</body>
</html>