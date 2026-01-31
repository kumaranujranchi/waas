<?php
// CRITICAL: Prevent any text output before JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Buffer all output to catch unexpected errors/warnings
ob_start();

header('Content-Type: application/json');

try {
    // 1. Load Configurations
    if (!file_exists(__DIR__ . '/../config/config.php') || !file_exists(__DIR__ . '/../config/database.php')) {
        throw new Exception("Configuration files not found");
    }

    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../config/database.php';

    // 2. Security: Only allow POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Method not allowed", 405);
    }

    // 3. Parse Input
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (!$input || !isset($input['message'])) {
        throw new Exception("Message is required");
    }

    $userMessage = trim($input['message']);
    $userName = $input['user_name'] ?? 'User';
    $isLeadCaptured = $input['is_lead_captured'] ?? false;

    // 4. PRE-FLIGHT DB CHECK
    // We try to connect manually first. If this fails, we SKIP loading models 
    // to avoid the Database class calling die() and breaking JSON response.
    $dbAvailable = false;
    try {
        if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . (defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');
            $options = defined('DB_OPTIONS') ? DB_OPTIONS : [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $testConn = new PDO($dsn, DB_USER, DB_PASS, $options);
            $dbAvailable = true;
            $testConn = null; // Close test connection
        }
    } catch (Exception $e) {
        // DB is down, but we continue so chatbot can at least respond (without dynamic data)
        // or we can allow it to fail gracefully later.
        $dbAvailable = false;
    }

    // 5. Build Knowledge Base
    $knowledgeBase = "";
    if ($dbAvailable) {
        require_once __DIR__ . '/../models/Product.php';
        $productModel = new Product();
        $products = $productModel->getAllProducts();

        if (!empty($products)) {
            $knowledgeBase .= "Current Services and Pricing:\n";
            foreach ($products as $product) {
                $knowledgeBase .= "- Service: " . $product['name'] . "\n";
                $knowledgeBase .= "  Description: " . ($product['short_description'] ?? 'Professional WaaS service') . "\n";

                // Get Pricing Plans
                $plans = $productModel->getProductPricingPlans($product['id'], true);
                if (!empty($plans)) {
                    $knowledgeBase .= "  Pricing Plans:\n";
                    foreach ($plans as $plan) {
                        // Handle column name 'plan_name' vs 'name' safely
                        $pName = $plan['plan_name'] ?? $plan['name'] ?? 'Standard';
                        $price = number_format((float) ($plan['price'] ?? 0), 0, '.', ',');
                        $cycle = $plan['billing_cycle'] ?? 'month';
                        $knowledgeBase .= "    * {$pName}: ₹{$price} / {$cycle}\n";
                    }
                }
                $knowledgeBase .= "\n";
            }
        }
    }

    // 6. Construct System Prompt
    $systemPrompt = "You are the sales and support AI assistant for \"SiteOnSub\", a Website as a Service (WaaS) platform.\n";
    $systemPrompt .= "Your goal is to answer visitor queries, overcome objections, and pitch the subscription model.\n";
    $systemPrompt .= "You must speak in \"Simple Hinglish\" (a mix of Hindi and English) that is friendly, clear, and non-technical.\n\n";

    if (!$isLeadCaptured) {
        $systemPrompt .= "CRITICAL: You are in \"LEAD CAPTURE MODE\".\n";
        $systemPrompt .= "Before answering complex questions, you MUST gather the user's Full Name, Email, and Phone Number.\n";
        $systemPrompt .= "- Do not ask for all three at once. Ask one item per message to keep it conversational.\n";
        $systemPrompt .= "- Be polite. Say something like \"Zaroor! Main aapki help karunga, par pehle kya aap apna naam bata sakte hain?\"\n";
        $systemPrompt .= "- Validation: Ensure Phone is 10 digits and Email looks real.\n";
        $systemPrompt .= "- ONCE YOU HAVE ALL THREE DETAILS (Name, Email, Phone), you MUST append this EXACT string at the end of your response:\n";
        $systemPrompt .= "  DATA_CAPTURE{\"name\": \"USER_NAME\", \"email\": \"USER_EMAIL\", \"phone\": \"USER_PHONE\"}\n";
        $systemPrompt .= "  Replacing the values with the actual data you collected.\n\n";
    } else {
        $systemPrompt .= "You are talking to a potential client named \"{$userName}\". Address them by name occasionally.\n\n";
    }

    $systemPrompt .= "Here is your training data:\n\n";

    // Static Training Data
    $systemPrompt .= "1. Brand & Service Overview\n";
    $systemPrompt .= "- Service Model: Website as a Service (WaaS)\n";
    $systemPrompt .= "- Pitch: \"SiteOnSub ek Website as a Service platform hai jisme aap bina upfront cost ke apni custom-coded website subscription model par le sakte hain.\"\n\n";

    $systemPrompt .= "2. Traditional Website vs SiteOnSub\n";
    $systemPrompt .= "- Traditional Problems: High upfront cost (lakhs), separate costs for development, hosting, database, maintenance. Vendor dependency.\n";
    $systemPrompt .= "- SiteOnSub Benefits: Development FREE, Hosting + Database included, Updates/Maintenance included, Monthly plans, No lock-in (cancel anytime).\n";
    $systemPrompt .= "- Value Proposition: Sirf hosting ke cost me poori website. Business owner tension-free.\n\n";

    $systemPrompt .= "3. Website Ownership & Exit Policy\n";
    $systemPrompt .= "- Ownership: Website is custom-coded for the client.\n";
    $systemPrompt .= "- Exit Options: a) Code + Data Handover: Source code provided, data migrated to client DB. b) Client Server Hosting: Hosted on client's server with data migration.\n";
    $systemPrompt .= "- Exit Charges: Nominal one-time fee depending on complexity.\n";
    $systemPrompt .= "- Safe Custody: Data kept for 1 month, Source code for 1 year after exit.\n\n";

    if ($dbAvailable) {
        $systemPrompt .= "4. Real-time Service & Pricing (FETCHED FROM DATABASE)\n";
        $systemPrompt .= $knowledgeBase . "\n";
    }

    $systemPrompt .= "5. Monthly Plan Inclusions\n";
    $systemPrompt .= "- Included: Development, Support, Maintenance, Site updates, Hosting, Database (if in plan).\n";
    $systemPrompt .= "- Not Included: Domain (Client buys domain for full ownership).\n\n";

    $systemPrompt .= "6. SEO Policy\n";
    $systemPrompt .= "- Free with Plan: On-page SEO, Technical SEO, Website-level fixes, Google Search Console error fixing.\n";
    $systemPrompt .= "- Client Role: Just report errors.\n";
    $systemPrompt .= "- Not Included: Backlink building, Off-page SEO.\n\n";

    $systemPrompt .= "7. Updates Policy\n";
    $systemPrompt .= "- 3 updates per month FREE (Content changes, minor fixes).\n";
    $systemPrompt .= "- More than 3 updates: Custom pricing based on complexity.\n\n";

    $systemPrompt .= "8. Support & Monitoring\n";
    $systemPrompt .= "- Monitoring: Daily monitoring of all websites.\n";
    $systemPrompt .= "- Server Issues: Prior email notification.\n";
    $systemPrompt .= "- Non-Server Issues: 24 hours recovery.\n";
    $systemPrompt .= "- Support: Monday - Saturday, 10 AM - 12 PM.\n";
    $systemPrompt .= "- Urgent: Mark ticket as High Priority.\n\n";

    $systemPrompt .= "9. Target Audience\n";
    $systemPrompt .= "- Startups, Small Businesses, Agencies, Enterprises, NGOs, E-commerce, Service businesses.\n\n";

    $systemPrompt .= "10. Tone Guidelines\n";
    $systemPrompt .= "- Language: Simple Hinglish.\n";
    $systemPrompt .= "- Style: Friendly, Clear, Non-technical.\n";
    $systemPrompt .= "- Focus: Cost saving, Ownership, No lock-in, Tension-free.\n\n";

    $systemPrompt .= "11. Closing Example\n";
    $systemPrompt .= "- \"Agar aap bina heavy investment ke ek professionally managed website chahte ho jisme ownership bhi aapki rahe, to SiteOnSub aapke liye perfect option hai 😊\"\n\n";

    $systemPrompt .= "IMPORTANT INSTRUCTIONS:\n";
    $systemPrompt .= "- If asked about pricing not mentioned in the dynamic data above, refer them to the pricing section on the website.\n";
    $systemPrompt .= "- Use the pricing from the \"Real-time Service & Pricing\" section above as the source of truth.\n";
    $systemPrompt .= "- Do not make up facts.\n";
    $systemPrompt .= "- Be concise.\n";
    $systemPrompt .= "- Use emojis occasionally to be friendly.\n";

    // 7. Call DeepSeek API
    $apiData = [
        'model' => 'deepseek-chat',
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userMessage]
        ],
        'temperature' => 0.7
    ];

    if (!defined('DEEPSEEK_API_KEY')) {
        throw new Exception("API Key configuration missing");
    }

    $ch = curl_init('https://api.deepseek.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . DEEPSEEK_API_KEY
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        throw new Exception("Request failed: " . $curlError);
    }

    $result = json_decode($response, true);

    if ($httpCode === 200 && isset($result['choices'][0]['message']['content'])) {
        $reply = $result['choices'][0]['message']['content'];

        // 8. Lead Capture Handling (if DB available)
        $capturedData = [];
        $leadCaptured = false;

        if ($dbAvailable && preg_match('/DATA_CAPTURE({.*?})/', $reply, $matches)) {
            $jsonStr = $matches[1];
            $data = json_decode($jsonStr, true);

            if ($data && isset($data['name'], $data['email'], $data['phone'])) {
                require_once __DIR__ . '/../models/Lead.php';
                $lead = new Lead(); // This calls Database::getInstance(), but we verified DB availability already
                $leadId = $lead->create($data['name'], $data['email'], $data['phone']);

                if ($leadId) {
                    $reply = str_replace($matches[0], "", $reply); // Remove capture string
                    $leadCaptured = true;
                    $capturedData = [
                        'name' => $data['name'],
                        'id' => $leadId
                    ];
                }
            }
        }

        // 9. Send Final Response
        ob_end_clean(); // Discard any buffered junk
        echo json_encode([
            'reply' => trim($reply),
            'lead_captured' => $leadCaptured,
            'user_data' => !empty($capturedData) ? $capturedData : null
        ]);

    } else {
        throw new Exception("API Error: " . ($result['error']['message'] ?? 'Unknown error'));
    }

} catch (Exception $e) {
    ob_end_clean(); // Discard buffer
    http_response_code(500); // 500 status but valid JSON body
    echo json_encode(['error' => 'Server Error: ' . $e->getMessage()]);
}
?>