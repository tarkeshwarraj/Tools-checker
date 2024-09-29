@include('pages.header')

        {{-- hero section --}}
        <div class="mt-5">
            <h5 class="text-center text-2xl font-bold mb-6 ">Email Sender</h5>
            <div class="flex flex-col md:gap-6 px-4">
                <div class="flex flex-row md:gap-6 w-full">
                    <div class="w-full">
                        <input type="text" name="from-name" id="fromname" class="border border-gray-300 rounded-md p-2 m mb-4 w-full" placeholder="FROM Name" class="resize-none">
                    </div>
                    <div class="w-full">
                        <input type="text" name="subject" id="subject" class="border border-gray-300 rounded-md p-2 mb-4 w-full" placeholder="Subject" class="resize-none">
                    </div>
                </div>
                <div class="w-full">
                    <textarea name="message" id="message" cols="30" rows="6" class="w-full border border-gray-300 rounded-md p-2 mb-4 resize-none" placeholder="Message" class="resize-none"></textarea>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-evenly md:gap-6 px-4">
                <div class="md:mt-8 w-full sm:w-[100%]">
                    <textarea name="emails" id="emails" class="w-full sm:w-[100%] h-32 border border-gray-300 rounded-md p-2 mb-4 resize-none" class="resize-none" placeholder="Recipient emails"></textarea>
                </div>
                
                <div class="md:mt-8 w-full sm:w-[100%]">
                    <textarea name="smtp" id="smtp"  class="w-full sm:w-[100%] h-32 border border-gray-300 rounded-md p-2 mb-4 resize-none" placeholder="adrien-group.fr|587|userID|password (one per line)" class="resize-none"></textarea>
                </div>
            </div>

            <div class="w-full flex justify-center  px-4 mb-4">
                <button onclick="sendMail()" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Send Mail</button>
            </div>

            {{-- Add a progress bar --}}
            <div id="progress-percentage" class="text-center hidden">0%</div>
            <div id="progress-bar" class="hidden w-full bg-gray-200 rounded-full h-4 mb-4">
                <div id="progress-fill" class="bg-blue-600 h-4 rounded-full" style="width: 0%"></div>
            </div>

            <div class="not-working w-full mb-3 h-32">
                <div id="not-working-smtp" class="w-full h-full overflow-x-hidden"></div>
            </div>
        </div>
    </div>
    

    @include('pages.footer')

    

    <script>
    // Set the CSRF token for Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function sendMail() {
        const emails = document.getElementById('emails').value.trim().split('\n');
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        const fromname = document.getElementById('fromname').value;
        const smtpList = document.getElementById('smtp').value.trim().split('\n');
        const notWorkingElement = document.getElementById('not-working-smtp');
        const progressBar = document.getElementById('progress-bar');
        const progressFill = document.getElementById('progress-fill');
        const progressPercentage = document.getElementById('progress-percentage');
        notWorkingElement.innerHTML = ''; // Clear previous messages

        // Show the progress bar and percentage and reset width and text
        progressBar.classList.remove('hidden');
        progressPercentage.classList.remove('hidden');
        progressFill.style.width = '0%';
        progressPercentage.innerText = '0%';

        const totalEmails = emails.length;
        let emailsProcessed = 0;
        let currentSmtpIndex = 0; // Keep track of the current SMTP server being used
        let smtpUsageCount = 0; // Track the number of emails sent with the current SMTP server
        const maxEmailsPerSmtp = 400; // Limit of emails per SMTP server

        for (const email of emails) {
            let sent = false;

            // Only change SMTP server if the current one fails or reaches 400 emails
            while (currentSmtpIndex < smtpList.length) {
                const smtp = smtpList[currentSmtpIndex];
                const [host, port, username, password] = smtp.split('|').map(item => item.trim());

                if (!host || !port || !username || !password) {
                    notWorkingElement.innerHTML += `Invalid SMTP details: ${smtp}.<br>`;
                    currentSmtpIndex++; // Move to the next SMTP server
                    smtpUsageCount = 0; // Reset the email count for the next server
                    continue;
                }

                const smtpString = `${host}|${port}|${username}|${password}`;

                try {
                    const response = await axios.post('/send-mail', {
                        email: email,
                        subject: subject,
                        message: message,
                        smtp: smtpString, // Send as a single string
                        fromname: fromname // Include FROM Name in the request
                    });

                    if (response.data.status === 'success') {
                        notWorkingElement.innerHTML += `Mail sent to ${email} using ${host} <br />`;
                        sent = true;
                        smtpUsageCount++; // Increment the count of emails sent with the current SMTP

                        if (smtpUsageCount >= maxEmailsPerSmtp) {
                            // Switch to the next SMTP server after 400 emails
                            currentSmtpIndex++;
                            smtpUsageCount = 0; // Reset email count for the next server
                        }
                        break; // Exit the while loop and continue with the next email
                    }
                } catch (error) {
                    console.error(`Error sending to ${email} using ${host}:`, error);
                    notWorkingElement.innerHTML += `Error sending to ${email} using ${host}. Trying next SMTP...<br>`;
                    currentSmtpIndex++; // Move to the next SMTP server only on failure
                    smtpUsageCount = 0; // Reset the email count for the next server
                }
            }

            if (!sent) {
                notWorkingElement.innerHTML += `Failed to send email to ${email} with all provided SMTP servers.<br>`;
            }

            // Update progress bar and percentage after each email
            emailsProcessed++;
            const progressPercent = Math.round((emailsProcessed / totalEmails) * 100);
            progressFill.style.width = `${progressPercent}%`;
            progressPercentage.innerText = `${progressPercent}%`;

            // Reset SMTP index if all SMTP servers have been used
            if (currentSmtpIndex >= smtpList.length) {
                currentSmtpIndex = 0; // Reset to the first SMTP server if needed
            }
        }

        // Hide the progress bar and percentage when all emails are processed
        progressBar.classList.add('hidden');
        progressPercentage.classList.add('hidden');
    }
</script>

</body>
</html>