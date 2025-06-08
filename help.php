<h1 id="announce-sumup">Announce SumUp!</h1>
<p><a href="http://makeapullrequest.com"><img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat"
            alt="Pull Requests Welcome"></a>
    <img src="https://shields.io/badge/fpp-AnnounceSumUp-brightgreen" alt="FPP SumUp Logo">
</p>

<p>Get yourself an iZettle device, take a donation &amp; trigger an effect on your light show.</p>
<ul>
    <li><a href="#getting-started">Getting Started</a></li>
    <li><a href="#fpp">FPP</a></li>
    <li><a href="#pushover-setup">Pushover Setup</a></li>
    <li><a href="#notes">Things To Note</a></li>
</ul>

<h2 id="getting-started">Getting Started</h2>
<p>You&#39;ll need the SumUp Donation andriod app to use this plugin.</p>
<p>This is a custom app that can be download <a
        href="https://fpp-zettle.s3.dualstack.eu-west-2.amazonaws.com/sumup_donation_app.apk">here</a></p>
<p>Note: This app is not publised on the andriod play store and will need to have <b>Install Unknown Apps</b> enabled on
    your
    andriod device.</p>

<h2 id="fpp">FPP</h2>
<p>Navigate to your FPP instance. </p>
<p>Click <code>&#39;Content Setup&#39; &gt; &#39;Plugin Manager&#39;</code></p>
<p>Install the <code>Announce SumUp</code> plugin.</p>
<p>Once installed, navigate to <code>&#39;Content Setup&#39; &gt; &#39;SumUp - Setup&#39;</code>.</p>
<p>On the setup page your can select what your want to happen once a transaction coming in from the SumUp Donation app.
</p>
<p>When a real transaction is received the plugin will log it to a transaction file. You can view transactions in
    <code>Status / Control &gt; SumUp - Status</code>. This page will also allow you to clear any transactions should
    you wish. This is mearly for you to see what / who has used your Zettle device to donate at your show.
</p>

<h2 id="pushover-setup">Pushover Setup</h2>
<p>Get notification sent your phone every time a donate is made. Pushover is free to use for 30 days. If you want to use
    it for longer there is a $5 USD one-time purchase fee. Check out the details at there website: <a
        href="https://pushover.net/" target="_blank">https://pushover.net</a></p>
<p>To get up and running with Pushover you will need to create an account and get two keys that will be need to
    everything to work. The two keys you need is the <strong>Application API Token</strong> and <strong>User
        Key</strong></p>
<p>You can find <strong>User Key</strong> on the first page you go after you login on the rigth hand side</p>
<p>To get the <strong>Application API Token</strong> first you need to create an application.</p>
<p>Navigate to your Pushover dashboard.</p>
<p>Scroll down to <code>Your Applications</code></p>
<p>Click <code>Create an Application/API Token</code></p>
<p>Ender a <code>Name</code> for your application then click <code>Create Application</code></p>
<p>Once your application is created you will see your API Token/Key</p>

<h2 id="notes">Things To Note</h2>
<ol>
    <li>The andriod device needs to been connected to the internet and have the SumUp Donation app loaded for every
        thing to work</li>
    <li>To stop your guests getting out of the app and getting to the main table you need to kiosk app. I recommend
        Fully Single App Kiosk from the google play store. Can be found <a
            href="https://play.google.com/store/apps/details?id=com.fullykiosk.singleapp&pcampaignid=web_share"
            target="_blank">here</a>. This is not a free app to use but it is the only one that works that i could find.
        There is a one off fee of 7.90 EUR per device plus VAT. This is need as it will remove the water mark from the
        app.</li>
    <li>Battey life on both the card reader and the andriod device does not last in the cold so would need to connected
        to power
    </li>
    <li>Bluetooth range on the card reader we found is not the best so we recommend that you keep in within 5
        feet of the andriod device</li>
    <li>The card reader is not water proof and would need to box to keep the water out. Here is a <a
            href="https://www.amazon.co.uk/dp/B08FC91HHV" target="_blank">link</a> to box that works well</li>
</ol>

<h2 id="privacy-policy">Privacy Policy</h2>
<h3 id="what-we-collect">What We Collect</h3>
<p>Absolutely nothing!</p>
<h3 id="what-we-don-t-collect">What We Don&#39;t Collect</h3>
<p>We do not collect or store any of your personal information. The information you submit via this plugin is
    transmitted between your Pi &amp; the Zettle API. Any transactions are kept on your device &amp; are retrievable
    from Zettle using your API Keys (client_id &amp; secret) should you clear them. </p>