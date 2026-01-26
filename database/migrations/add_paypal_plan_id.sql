-- Migration: Add PayPal Plan ID to pricing_plans
ALTER TABLE pricing_plans ADD COLUMN paypal_plan_id VARCHAR(255) DEFAULT NULL AFTER razorpay_plan_id;
