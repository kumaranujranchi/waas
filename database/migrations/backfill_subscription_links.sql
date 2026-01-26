-- Migration: Backfill subscription_id for existing orders
-- This script attempts to link existing orders to subscriptions based on user_id and product/plan matches.

UPDATE orders o
JOIN order_items oi ON o.id = oi.order_id
JOIN subscriptions s ON o.user_id = s.user_id 
    AND oi.product_id = s.product_id 
    AND oi.plan_id = s.plan_id
SET o.subscription_id = s.id
WHERE o.subscription_id IS NULL;
