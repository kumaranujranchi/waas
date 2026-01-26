<?php
/**
 * Mail Utility Class
 * Handles sending emails using PHPMailer and Hostinger SMTP
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

class Mail
{
    /**
     * Get a configured PHPMailer instance
     */
    private static function getMailer()
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = (SMTP_ENCRYPTION === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            // Sender
            $mail->setFrom(SITE_EMAIL, SITE_NAME);

            return $mail;
        } catch (Exception $e) {
            error_log("Mailer configuration error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Send Order Confirmation Email
     */
    public static function sendOrderConfirmation($toEmail, $userName, $orderDetails)
    {
        $mail = self::getMailer();
        if (!$mail)
            return false;

        try {
            $mail->addAddress($toEmail, $userName);
            $mail->addBCC(SITE_EMAIL, 'Admin Notification'); // Notify admin too

            $mail->isHTML(true);
            $mail->Subject = 'Order Confirmed - ' . $orderDetails['order_number'] . ' | ' . SITE_NAME;

            // Basic HTML Template
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h1 style='color: #4f46e5; margin: 0;'>" . SITE_NAME . "</h1>
                        <p style='color: #6b7280;'>Order Confirmation</p>
                    </div>
                    <p>Hello <strong>$userName</strong>,</p>
                    <p>Thank you for choosing SiteOnSub! Your order has been placed successfully and is now in our system.</p>
                    
                    <div style='background: #f9fafb; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                        <p style='margin: 0; font-weight: bold;'>Order Summary:</p>
                        <p style='margin: 5px 0;'>Order ID: #" . substr($orderDetails['order_number'], -8) . "</p>
                        <p style='margin: 5px 0;'>Amount: " . CURRENCY_SYMBOL . number_format($orderDetails['final_amount'], 2) . "</p>
                        <p style='margin: 5px 0;'>Status: <span style='color: #059669; font-weight: bold;'>Paid</span></p>
                    </div>

                    <p>Our team will begin working on your requirements shortly. You can track your project status in your client dashboard.</p>
                    
                    <div style='text-align: center; margin-top: 30px;'>
                        <a href='" . SITE_URL . "/dashboard/orders.php' style='background: #4f46e5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>View Dashboard</a>
                    </div>
                    
                    <hr style='border: 0; border-top: 1px solid #eee; margin-top: 40px;'>
                    <p style='font-size: 12px; color: #9ca3af; text-align: center;'>If you have any questions, reply to this email or visit our support portal.</p>
                </div>
            ";

            $mail->Body = $body;
            return $mail->send();
        } catch (Exception $e) {
            error_log("Order email error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Consultation Confirmation Email
     */
    public static function sendBookingConfirmation($toEmail, $userName, $bookingDetails)
    {
        $mail = self::getMailer();
        if (!$mail)
            return false;

        try {
            $mail->addAddress($toEmail, $userName);

            $mail->isHTML(true);
            $mail->Subject = 'Consultation Confirmed | ' . SITE_NAME;

            $dateFormatted = date('M d, Y', strtotime($bookingDetails['preferred_date']));
            $timeFormatted = date('h:i A', strtotime($bookingDetails['preferred_time']));

            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h1 style='color: #4f46e5; margin: 0;'>" . SITE_NAME . "</h1>
                    </div>
                    <p>Hello <strong>$userName</strong>,</p>
                    <p>Your consultation request has been **Confirmed**! Our experts are looking forward to discussing your business requirements.</p>
                    
                    <div style='background: #f0fdf4; padding: 15px; border-radius: 8px; border-left: 4px solid #059669; margin: 20px 0;'>
                        <p style='margin: 0; font-weight: bold; color: #059669;'>Booking Details:</p>
                        <p style='margin: 5px 0;'><strong>Date:</strong> $dateFormatted</p>
                        <p style='margin: 5px 0;'><strong>Time:</strong> $timeFormatted</p>
                    </div>

                    <p>Please be available at the scheduled time. We will contact you via your registered phone/email.</p>
                    
                    <hr style='border: 0; border-top: 1px solid #eee; margin-top: 40px;'>
                    <p style='font-size: 12px; color: #9ca3af;'>SiteOnSub - Delivering Quality Software Solutions.</p>
                </div>
            ";

            $mail->Body = $body;
            return $mail->send();
        } catch (Exception $e) {
            error_log("Booking email error: " . $e->getMessage());
            return false;
        }
    }
}
