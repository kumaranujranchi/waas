<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

// Handle Lead Saving
if (isset($input['action']) && $input['action'] === 'save_lead') {
    require_once __DIR__ . '/../models/Lead.php';

    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';

    if (empty($name) || empty($email) || empty($phone)) {
        http_response_code(400);
        echo json_encode(['error' => 'Name, Email and Phone are required']);
        exit;
    }

    $lead = new Lead();
    $leadId = $lead->create($name, $email, $phone);

    if ($leadId) {
        echo json_encode(['success' => true, 'lead_id' => $leadId]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save lead']);
    }
    exit;
}

if (empty($userMessage)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

$userName = $input['user_name'] ?? 'User';
$isLeadCaptured = $input['is_lead_captured'] ?? false;

// --- Start Dynamic Knowledge Base ---
require_once __DIR__ . '/../models/Product.php';
$productModel = new Product();
$products = $productModel->getAllProducts();
$knowledgeBase = "";

if (!empty($products)) {
    $knowledgeBase .= "Current Services and Pricing:\n";
    foreach ($products as $product) {
        $knowledgeBase .= "- Service: " . $product['name'] . "\n";
        $knowledgeBase .= "  Description: " . ($product['short_description'] ?? 'Professional WaaS service') . "\n";

        $plans = $productModel->getProductPricingPlans($product['id'], true);
        if (!empty($plans)) {
            $knowledgeBase .= "  Pricing Plans:\n";
            foreach ($plans as $plan) {
                $knowledgeBase .= "    * " . $plan['name'] . ": ₹" . number_with_commas($plan['price']) . " / " . $plan['billing_cycle'] . "\n";
            }
        }
        $knowledgeBase .= "\n";
    }
}

function number_with_commas($n)
{
    return number_format($n, 0, '.', ',');
}
// --- End Dynamic Knowledge Base ---

// System Prompt / Training Data
$systemPrompt = <<<EOT
You are the sales and support AI assistant for "SiteOnSub", a Website as a Service (WaaS) platform.
Your goal is to answer visitor queries, overcome objections, and pitch the subscription model.
You must speak in "Simple Hinglish" (a mix of Hindi and English) that is friendly, clear, and non-technical.

EOT;

if (!$isLeadCaptured) {
    $systemPrompt .= <<<EOT
CRITICAL: You are in "LEAD CAPTURE MODE".
Before answering complex questions, you MUST gather the user's Full Name, Email, and Phone Number.
- Do not ask for all three at once. Ask one item per message to keep it conversational.
- Be polite. Say something like "Zaroor! Main aapki help karunga, par pehle kya aap apna naam bata sakte hain?"
- Validation: Ensure Phone is 10 digits and Email looks real.
- ONCE YOU HAVE ALL THREE DETAILS (Name, Email, Phone), you MUST append this EXACT string at the end of your response:
  DATA_CAPTURE{"name": "USER_NAME", "email": "USER_EMAIL", "phone": "USER_PHONE"}
  Replacing the values with the actual data you collected.

EOT;
} else {
    $systemPrompt .= "You are talking to a potential client named \"{$userName}\". Address them by name occasionally.\n";
}

$systemPrompt .= <<<EOT

Here is your training data:

1. Brand & Service Overview
- Service Model: Website as a Service (WaaS)
- Pitch: "SiteOnSub ek Website as a Service platform hai jisme aap bina upfront cost ke apni custom-coded website subscription model par le sakte hain."

2. Traditional Website vs SiteOnSub
- Traditional Problems: High upfront cost (lakhs), separate costs for development, hosting, database, maintenance. Vendor dependency.
- SiteOnSub Benefits: Development FREE, Hosting + Database included, Updates/Maintenance included, Monthly plans, No lock-in (cancel anytime).
- Value Proposition: Sirf hosting ke cost me poori website. Business owner tension-free.

3. Website Ownership & Exit Policy
- Ownership: Website is custom-coded for the client.
- Exit Options:
  a) Code + Data Handover: Source code provided, data migrated to client DB.
  b) Client Server Hosting: Hosted on client's server with data migration.
- Exit Charges: Nominal one-time fee depending on complexity.
- Safe Custody: Data kept for 1 month, Source code for 1 year after exit.

4. Real-time Service & Pricing (FETCHED FROM DATABASE)
{$knowledgeBase}

5. Monthly Plan Inclusions
- Included: Development, Support, Maintenance, Site updates, Hosting, Database (if in plan).
- Not Included: Domain (Client buys domain for full ownership).

6. SEO Policy
- Free with Plan: On-page SEO, Technical SEO, Website-level fixes, Google Search Console error fixing.
- Client Role: Just report errors.
- Not Included: Backlink building, Off-page SEO.

7. Updates Policy
- 3 updates per month FREE (Content changes, minor fixes).
- More than 3 updates: Custom pricing based on complexity.

8. Support & Monitoring
- Monitoring: Daily monitoring of all websites.
- Server Issues: Prior email notification.
- Non-Server Issues: 24 hours recovery.
- Support: Monday - Saturday, 10 AM - 12 PM.
- Urgent: Mark ticket as High Priority.

9. Target Audience
- Startups, Small Businesses, Agencies, Enterprises, NGOs, E-commerce, Service businesses.

10. Tone Guidelines
- Language: Simple Hinglish.
- Style: Friendly, Clear, Non-technical.
- Focus: Cost saving, Ownership, No lock-in, Tension-free.

11. Closing Example
- "Agar aap bina heavy investment ke ek professionally managed website chahte ho jisme ownership bhi aapki rahe, to SiteOnSub aapke liye perfect option hai 😊"

IMPORTANT INSTRUCTIONS:
- If asked about pricing not mentioned in the dynamic data above, refer them to the pricing section on the website.
- Use the pricing from the "Real-time Service & Pricing" section above as the source of truth.
- Do not make up facts.
- Be concise.
- Use emojis occasionally to be friendly.
EOT;

// Prepare payload for DeepSeek API
$data = [
    'model' => 'deepseek-chat',
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'temperature' => 0.7
];

// Send request
$ch = curl_init('https://api.deepseek.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . DEEPSEEK_API_KEY
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Request failed: ' . curl_error($ch)]);
} else {
    $result = json_decode($response, true);
    if ($httpCode === 200 && isset($result['choices'][0]['message']['content'])) {
        $reply = $result['choices'][0]['message']['content'];

        // Detect DATA_CAPTURE signal
        if (preg_match('/DATA_CAPTURE({.*?})/', $reply, $matches)) {
            $captureData = json_decode($matches[1], true);
            if ($captureData && isset($captureData['name'], $captureData['email'], $captureData['phone'])) {
                require_once __DIR__ . '/../models/Lead.php';
                $lead = new Lead();
                $leadId = $lead->create($captureData['name'], $captureData['email'], $captureData['phone']);

                if ($leadId) {
                    // Clean the reply for the user
                    $reply = str_replace($matches[0], "", $reply);
                    echo json_encode([
                        'reply' => trim($reply),
                        'lead_captured' => true,
                        'user_data' => [
                            'name' => $captureData['name'],
                            'id' => $leadId
                        ]
                    ]);
                    exit;
                }
            }
        }

        echo json_encode(['reply' => $reply]);
    } else {
        // Fallback or error handling
        error_log("DeepSeek API Error: " . $response);
        echo json_encode(['error' => 'Sorry, main abhi answer nahi kar pa raha hu. Please try again later.']);
    }
}

curl_close($ch);
