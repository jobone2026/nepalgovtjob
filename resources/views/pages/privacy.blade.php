@extends('layouts.app')

@section('title', 'Privacy Policy - JobOne.in')
@section('description', 'Privacy Policy for JobOne.in - Government Job Alerts and Notifications')

@section('content')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .privacy-section {
            margin-bottom: 2rem;
        }
        .privacy-section h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .privacy-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .privacy-section p {
            margin-bottom: 1rem;
            line-height: 1.7;
            color: #4b5563;
        }
        .privacy-section ul {
            list-style-type: disc;
            margin-left: 2rem;
            margin-bottom: 1rem;
        }
        .privacy-section li {
            margin-bottom: 0.5rem;
            color: #4b5563;
        }
        .highlight-box {
            background: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 0.5rem;
        }
    </style>

    <div class="glass-effect rounded-lg shadow-md p-6 md:p-8 max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Privacy Policy</h1>
        <p class="text-sm text-gray-600 mb-8"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>

        <div class="highlight-box">
            <p class="text-sm font-semibold text-gray-800 mb-2">📱 For Mobile App Users:</p>
            <p class="text-sm text-gray-700">
                This privacy policy applies to both our website (jobone.in) and mobile application. 
                We are committed to protecting your privacy and ensuring transparency in how we handle your information.
            </p>
        </div>
        
        <div class="privacy-section">
            <h2>1. Introduction</h2>
            <p>
                Welcome to JobOne.in ("we", "us", "our", or "the Service"). We operate the website jobone.in and the JobOne mobile application. 
                This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our Service.
            </p>
            <p>
                <strong>Our Purpose:</strong> JobOne.in is a government job information aggregator and notification service. 
                We provide alerts and information about government job opportunities, admit cards, results, and related content. 
                We do not collect, store, or process applications for government jobs. Users must always visit the official government websites to apply.
            </p>
        </div>

        <div class="privacy-section">
            <h2>2. Information We Collect</h2>
            
            <h3>2.1 Information You Provide</h3>
            <p>We may collect the following information when you use our Service:</p>
            <ul>
                <li><strong>Push Notification Token:</strong> Device token for sending job alerts (if you enable notifications)</li>
                <li><strong>Contact Information:</strong> Email address (only if you contact us)</li>
                <li><strong>Preferences:</strong> Job categories and states you're interested in (stored locally on your device)</li>
            </ul>

            <h3>2.2 Information Automatically Collected</h3>
            <ul>
                <li><strong>Device Information:</strong> Device type, operating system version, unique device identifiers</li>
                <li><strong>Usage Data:</strong> Pages viewed, time spent, features used</li>
                <li><strong>Log Data:</strong> IP address, browser type, access times</li>
                <li><strong>Analytics:</strong> We use Google Analytics to understand how users interact with our Service</li>
            </ul>

            <h3>2.3 Information We DO NOT Collect</h3>
            <p>We want to be clear about what we don't collect:</p>
            <ul>
                <li>We do NOT collect your name, address, or phone number</li>
                <li>We do NOT collect your educational qualifications or resume</li>
                <li>We do NOT collect your Aadhaar, PAN, or any government ID numbers</li>
                <li>We do NOT collect your location data</li>
                <li>We do NOT access your contacts, photos, or other personal files</li>
                <li>We do NOT collect any sensitive personal information</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>3. How We Use Your Information</h2>
            <p>We use the collected information for the following purposes:</p>
            <ul>
                <li><strong>Notifications:</strong> To send you alerts about new government job postings, admit cards, and results</li>
                <li><strong>Service Improvement:</strong> To understand how users interact with our Service and improve it</li>
                <li><strong>Technical Support:</strong> To respond to your inquiries and provide customer support</li>
                <li><strong>Analytics:</strong> To analyze usage patterns and optimize our Service</li>
                <li><strong>Legal Compliance:</strong> To comply with applicable laws and regulations</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>4. Push Notifications</h2>
            <p>
                Our mobile app uses Firebase Cloud Messaging (FCM) to send push notifications about new job postings. 
                When you install our app and grant notification permission:
            </p>
            <ul>
                <li>Your device receives a unique token from Firebase</li>
                <li>We use this token to send job alerts to your device</li>
                <li>You can disable notifications anytime in your device settings</li>
                <li>Disabling notifications does not affect other app features</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>5. Third-Party Services</h2>
            <p>We use the following third-party services:</p>
            <ul>
                <li><strong>Google Analytics:</strong> For usage analytics and understanding user behavior</li>
                <li><strong>Firebase Cloud Messaging:</strong> For sending push notifications</li>
                <li><strong>Google AdSense:</strong> For displaying advertisements (if applicable)</li>
            </ul>
            <p>
                These services may collect information as described in their respective privacy policies. 
                We recommend reviewing their privacy policies:
            </p>
            <ul>
                <li>Google Privacy Policy: <a href="https://policies.google.com/privacy" target="_blank" class="text-blue-600 hover:underline">https://policies.google.com/privacy</a></li>
                <li>Firebase Privacy: <a href="https://firebase.google.com/support/privacy" target="_blank" class="text-blue-600 hover:underline">https://firebase.google.com/support/privacy</a></li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>6. Data Sharing and Disclosure</h2>
            <p><strong>We do NOT sell, trade, or rent your personal information to third parties.</strong></p>
            <p>We may share information only in the following circumstances:</p>
            <ul>
                <li><strong>Service Providers:</strong> With trusted third-party services (like Firebase) that help us operate our Service</li>
                <li><strong>Legal Requirements:</strong> If required by law or to protect our rights</li>
                <li><strong>Business Transfer:</strong> In connection with a merger, acquisition, or sale of assets</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>7. Data Security</h2>
            <p>
                We implement appropriate technical and organizational measures to protect your information:
            </p>
            <ul>
                <li>Secure HTTPS connection for all data transmission</li>
                <li>Regular security updates and monitoring</li>
                <li>Limited access to personal information</li>
                <li>Secure server infrastructure</li>
            </ul>
            <p>
                However, no method of transmission over the Internet or electronic storage is 100% secure. 
                While we strive to protect your information, we cannot guarantee absolute security.
            </p>
        </div>

        <div class="privacy-section">
            <h2>8. Data Retention</h2>
            <p>
                We retain your information only as long as necessary for the purposes outlined in this Privacy Policy:
            </p>
            <ul>
                <li>Push notification tokens are retained while you have the app installed</li>
                <li>Analytics data is retained according to Google Analytics retention policies</li>
                <li>Log data is retained for 90 days for security and troubleshooting purposes</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>9. Your Rights and Choices</h2>
            <p>You have the following rights regarding your information:</p>
            <ul>
                <li><strong>Opt-Out of Notifications:</strong> Disable push notifications in your device settings</li>
                <li><strong>Uninstall:</strong> Remove the app to stop all data collection</li>
                <li><strong>Access:</strong> Request information about data we have collected</li>
                <li><strong>Deletion:</strong> Request deletion of your data by contacting us</li>
                <li><strong>Correction:</strong> Request correction of inaccurate information</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>10. Children's Privacy</h2>
            <p>
                Our Service is intended for users aged 16 and above. We do not knowingly collect personal information from children under 16. 
                If you are a parent or guardian and believe your child has provided us with personal information, please contact us, 
                and we will delete such information.
            </p>
        </div>

        <div class="privacy-section">
            <h2>11. Important Disclaimer</h2>
            <div class="highlight-box">
                <p class="font-semibold text-gray-800 mb-2">⚠️ Please Note:</p>
                <ul class="text-sm">
                    <li>JobOne.in is an information aggregator and notification service only</li>
                    <li>We are NOT affiliated with any government organization or recruitment body</li>
                    <li>We do NOT accept or process job applications</li>
                    <li>We do NOT guarantee job placement or selection</li>
                    <li>Users must ALWAYS visit official government websites to apply for jobs</li>
                    <li>We are NOT responsible for the accuracy of information from third-party sources</li>
                    <li>Always verify information on official government websites before applying</li>
                </ul>
            </div>
        </div>

        <div class="privacy-section">
            <h2>12. Changes to This Privacy Policy</h2>
            <p>
                We may update this Privacy Policy from time to time. We will notify you of any changes by:
            </p>
            <ul>
                <li>Posting the new Privacy Policy on this page</li>
                <li>Updating the "Last Updated" date</li>
                <li>Sending a notification through the app (for significant changes)</li>
            </ul>
            <p>
                We encourage you to review this Privacy Policy periodically for any changes.
            </p>
        </div>

        <div class="privacy-section">
            <h2>13. Contact Us</h2>
            <p>
                If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:
            </p>
            <div class="bg-gray-50 p-4 rounded-lg mt-4">
                <p class="mb-2"><strong>Email:</strong> <a href="mailto:jobone2026@gmail.com" class="text-blue-600 hover:underline">jobone2026@gmail.com</a></p>
                <p class="mb-2"><strong>Website:</strong> <a href="https://jobone.in" class="text-blue-600 hover:underline">https://jobone.in</a></p>
                <p><strong>Response Time:</strong> We aim to respond to all inquiries within 48 hours</p>
            </div>
        </div>

        <div class="privacy-section">
            <h2>14. Consent</h2>
            <p>
                By using our Service (website or mobile app), you consent to this Privacy Policy and agree to its terms. 
                If you do not agree with this Privacy Policy, please do not use our Service.
            </p>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-8 rounded">
            <p class="text-sm text-gray-700">
                <strong>For App Store Reviewers:</strong> This privacy policy is publicly accessible at 
                <a href="https://jobone.in/privacy-policy" class="text-blue-600 hover:underline font-semibold">https://jobone.in/privacy-policy</a> 
                and complies with Google Play Store and Apple App Store requirements.
            </p>
        </div>
    </div>
@endsection
