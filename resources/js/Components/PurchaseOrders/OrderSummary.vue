<script setup>
import { computed } from 'vue';
import InputNumber from 'primevue/inputnumber';

const props = defineProps({
  subtotal: {
    type: Number,
    required: true
  },
  taxRate: {
    type: Number,
    required: true
  },
  additionalCosts: {
    type: Number,
    required: true
  }
});

const emit = defineEmits(['update:taxRate', 'update:additionalCosts']);

const taxAmount = computed(() => (props.subtotal * props.taxRate) / 100);
const totalCost = computed(() => props.subtotal + taxAmount.value + props.additionalCosts);

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value);
};
</script>

<template>
  <div class="order-summary">
    <h2>Order Summary</h2>

    <div class="row">
      <span>Subtotal</span>
      <span class="amount">{{ formatCurrency(subtotal) }}</span>
    </div>

    <div class="row">
      <span>Tax Rate (%)</span>
      <InputNumber
        :modelValue="taxRate"
        @update:modelValue="$emit('update:taxRate', $event)"
        :minFractionDigits="2"
        :maxFractionDigits="2"
        :useGrouping="false"
        class="amount-input"
      />
    </div>

    <div class="row">
      <span>Tax Amount</span>
      <span class="amount">{{ formatCurrency(taxAmount) }}</span>
    </div>

    <div class="row">
      <span>Additional Costs</span>
      <InputNumber
        :modelValue="additionalCosts"
        @update:modelValue="$emit('update:additionalCosts', $event)"
        mode="currency"
        currency="USD"
        class="amount-input"
      />
    </div>

    <div class="row total">
      <span>Total</span>
      <span class="amount">{{ formatCurrency(totalCost) }}</span>
    </div>
  </div>
</template>

<style scoped>
.order-summary {
  background: white;
  border-radius: 6px;
  padding: 1.5rem;
  width: 400px;
}

.order-summary h2 {
  font-size: 1.25rem;
  margin-bottom: 1.5rem;
  color: var(--text-color);
}

.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  position: relative;
}

.row > span:first-child {
  color: var(--text-color-secondary);
}

.amount {
  font-family: var(--font-family);
  text-align: right;
  min-width: 100px;
}

.amount-input {
  width: 100px;
}

:deep(.p-inputnumber) {
  width: 100px;
}

:deep(.p-inputnumber-input) {
  text-align: right;
  border: none;
  background: transparent;
  padding: 0;
  font-family: var(--font-family);
  color: var(--text-color);
}

:deep(.p-inputtext:enabled:focus) {
  box-shadow: none;
  border: none;
  outline: none;
}

.total {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--surface-200);
  font-weight: 600;
}
</style>
