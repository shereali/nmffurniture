<template>
  <div class="modal-backdrop" @click.self="$emit('close')">
    <div class="modal-content invoice-modal animate-slide-up">
      <!-- Toolbar (Hidden when printing) -->
      <div class="no-print flex items-center justify-between" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-file-invoice" style="color: var(--color-secondary-dark); font-size: 1.25rem;"></i>
          <span style="font-weight: 700; font-size: 1.1rem;">Official Tax Invoice & Factory Dispatch Slip</span>
        </div>
        <div class="flex items-center gap-2">
          <button @click="printInvoice" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-print"></i> Print / Save PDF
          </button>
          <button @click="$emit('close')" class="btn btn-outline btn-sm">
            Close
          </button>
        </div>
      </div>

      <!-- Printable A4 Sheet -->
      <div class="invoice-sheet" id="printable-invoice">
        <!-- Invoice Header -->
        <div class="invoice-header">
          <div>
            <div class="brand-title">NMF <span style="color: var(--color-secondary-dark);">FURNITURE</span></div>
            <div class="company-sub">NMFFURNITURE SDN BHD (SSM 1400875-P)</div>
            <div class="company-address">
              Bukit Jelutong Factory & Assembly Hub, Shah Alam, Selangor<br />
              Flagship Showrooms: Petaling Jaya & Shah Alam<br />
              WhatsApp Logistics: +60 19-258 9920 • Support: care@nmffurniture.my
            </div>
          </div>
          <div style="text-align: right;">
            <div class="doc-badge">OFFICIAL INVOICE</div>
            <div style="font-size: 1.25rem; font-weight: 800; font-family: monospace; color: var(--color-primary); margin-top: 0.5rem;">
              {{ order.order_number }}
            </div>
            <div style="font-size: 0.85rem; color: var(--color-text-muted); margin-top: 0.25rem;">
              Date: {{ formatDate(order.created_at) }}
            </div>
            <div style="margin-top: 0.5rem;">
              <span :class="['payment-pill', order.payment_status]">
                {{ (order.payment_status || 'PAID').toUpperCase() }}
              </span>
            </div>
          </div>
        </div>

        <div class="invoice-divider"></div>

        <!-- Customer & Delivery Info -->
        <div class="invoice-parties">
          <div>
            <div class="party-label">BILLED & DELIVERED TO:</div>
            <div class="party-name">{{ order.customer_name }}</div>
            <div class="party-detail">
              <i class="fa-solid fa-phone" style="width: 16px;"></i> {{ order.customer_phone }}
            </div>
            <div class="party-detail" v-if="order.customer_email">
              <i class="fa-solid fa-envelope" style="width: 16px;"></i> {{ order.customer_email }}
            </div>
            <div class="party-detail" style="margin-top: 0.35rem;">
              <i class="fa-solid fa-location-dot" style="width: 16px;"></i>
              {{ order.shipping_address }}, {{ order.shipping_city }}, {{ order.shipping_postcode }} {{ order.shipping_state }}
            </div>
          </div>

          <div style="text-align: right;">
            <div class="party-label">LOGISTICS & ORDER SPECS:</div>
            <div class="party-detail"><strong>Fulfillment:</strong> {{ (order.order_status || 'PROCESSING').toUpperCase() }}</div>
            <div class="party-detail"><strong>Delivery Method:</strong> Direct Factory White-Glove</div>
            <div class="party-detail"><strong>Warranty:</strong> 5-Year Solid Meranti Chassis</div>
          </div>
        </div>

        <!-- Itemized Table -->
        <table class="invoice-table">
          <thead>
            <tr>
              <th style="width: 45%;">Handcrafted Piece / Description</th>
              <th style="width: 25%;">Fabric / Finish Variant</th>
              <th style="text-align: center; width: 10%;">Qty</th>
              <th style="text-align: right; width: 20%;">Amount (RM)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in order.items" :key="idx">
              <td>
                <div style="font-weight: 700; color: var(--color-primary);">{{ item.product_name }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-muted);">SKU: {{ item.product_sku || 'NMF-CUSTOM' }}</div>
              </td>
              <td style="font-size: 0.85rem; color: var(--color-text-main);">
                {{ item.variant_option || 'Factory Standard Spec' }}
              </td>
              <td style="text-align: center; font-weight: 600;">
                {{ item.quantity }}
              </td>
              <td style="text-align: right; font-weight: 700;">
                {{ Number(item.total || (item.price * item.quantity)).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Totals Breakdown -->
        <div class="invoice-totals">
          <div style="width: 55%; font-size: 0.8rem; color: var(--color-text-muted); line-height: 1.6;">
            <strong>5-Year Warranty Guarantee:</strong> NMFFurniture warrants the solid kiln-dried Malaysian hardwood structural frame against manufacturing defects for 5 years from delivery date. Keep this invoice for lifetime after-sales care.
          </div>
          <div style="width: 40%;">
            <div class="total-row">
              <span>Subtotal:</span>
              <span>RM {{ Number(order.subtotal || order.total).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
            </div>
            <div v-if="order.discount_amount && Number(order.discount_amount) > 0" class="total-row" style="color: #2E7D32;">
              <span>Voucher Discount ({{ order.coupon_code }}):</span>
              <span>- RM {{ Number(order.discount_amount).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
            </div>
            <div class="total-row">
              <span>Peninsular Delivery & Assembly:</span>
              <span>{{ Number(order.shipping_fee || 0) === 0 ? 'FREE' : `RM ${Number(order.shipping_fee).toFixed(2)}` }}</span>
            </div>
            <div class="total-divider"></div>
            <div class="total-row grand-total">
              <span>TOTAL DUE / PAID:</span>
              <span>RM {{ Number(order.total).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
            </div>
          </div>
        </div>

        <!-- Footer Sign-off -->
        <div class="invoice-footer">
          <div>
            <p style="font-size: 0.75rem; color: var(--color-text-muted); margin: 0;">
              Handcrafted in Bukit Jelutong, Selangor • 100% Malaysian Owned & Manufactured
            </p>
          </div>
          <div style="text-align: right;">
            <div style="border-top: 1px dashed #CCC; width: 180px; padding-top: 0.35rem; font-size: 0.75rem; color: var(--color-text-muted);">
              Authorized Factory Signature
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  order: any
}>()

defineEmits(['close'])

function formatDate(dateStr: string) {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleDateString('en-MY', { day: 'numeric', month: 'short', year: 'numeric' })
}

function printInvoice() {
  window.print()
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.7);
  backdrop-filter: blur(4px);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}

.invoice-modal {
  background: #FFFFFF;
  border-radius: var(--radius-sm);
  width: 100%;
  max-width: 820px;
  max-height: 94vh;
  overflow-y: auto;
  padding: 2rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.invoice-sheet {
  background: #FFFFFF;
  color: #1E293B;
  font-family: var(--font-sans);
}

.invoice-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.brand-title {
  font-size: 1.6rem;
  font-weight: 800;
  font-family: var(--font-heading);
  letter-spacing: 0.05em;
  color: #161719;
}

.company-sub {
  font-size: 0.8rem;
  font-weight: 700;
  color: #475569;
  margin-top: 0.2rem;
}

.company-address {
  font-size: 0.78rem;
  color: #64748B;
  margin-top: 0.35rem;
  line-height: 1.5;
}

.doc-badge {
  background: #161719;
  color: #FFFFFF;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  padding: 0.25rem 0.65rem;
  border-radius: 4px;
  display: inline-block;
}

.payment-pill {
  font-size: 0.72rem;
  font-weight: 800;
  padding: 0.25rem 0.6rem;
  border-radius: 4px;
  letter-spacing: 0.05em;
}

.payment-pill.paid {
  background: #DCFCE7;
  color: #15803D;
}

.payment-pill.pending {
  background: #FEF3C7;
  color: #B45309;
}

.invoice-divider {
  height: 1px;
  background: #E2E8F0;
  margin: 1.5rem 0;
}

.invoice-parties {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1.75rem;
  font-size: 0.85rem;
}

.party-label {
  font-size: 0.7rem;
  font-weight: 800;
  color: #64748B;
  letter-spacing: 0.08em;
  margin-bottom: 0.35rem;
}

.party-name {
  font-size: 1rem;
  font-weight: 700;
  color: #0F172A;
  margin-bottom: 0.2rem;
}

.party-detail {
  font-size: 0.82rem;
  color: #475569;
  line-height: 1.5;
}

.invoice-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.85rem;
  margin-bottom: 1.75rem;
}

.invoice-table th {
  background: #F8FAFC;
  color: #475569;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 0.72rem;
  letter-spacing: 0.05em;
  padding: 0.75rem 1rem;
  border-top: 1px solid #E2E8F0;
  border-bottom: 1px solid #E2E8F0;
  text-align: left;
}

.invoice-table td {
  padding: 0.9rem 1rem;
  border-bottom: 1px solid #F1F5F9;
}

.invoice-totals {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding-top: 1rem;
  border-top: 1px solid #E2E8F0;
  margin-bottom: 2rem;
}

.total-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.85rem;
  margin-bottom: 0.4rem;
  color: #475569;
}

.total-divider {
  height: 1px;
  background: #CBD5E1;
  margin: 0.6rem 0;
}

.grand-total {
  font-size: 1.05rem;
  font-weight: 800;
  color: #0F172A;
}

.invoice-footer {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  border-top: 1px solid #E2E8F0;
  padding-top: 1.5rem;
}

/* Print Optimization */
@media print {
  .no-print {
    display: none !important;
  }
  .modal-backdrop {
    position: static;
    background: transparent;
    padding: 0;
  }
  .invoice-modal {
    max-width: 100%;
    max-height: none;
    box-shadow: none;
    padding: 0;
  }
}
</style>
