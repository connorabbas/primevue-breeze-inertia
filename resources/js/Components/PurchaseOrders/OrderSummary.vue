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
</script>

<template>
  <div class="summary-content">
    <h3 class="text-lg font-bold mb-4">Order Summary</h3>

    <div class="field-row">
      <span>Subtotal</span>
      <InputNumber
        :modelValue="subtotal"
        :readonly="true"
        mode="currency"
        currency="USD"
        class="w-32"
        :inputStyle="{ height: '2rem' }"
      />
    </div>

    <div class="field-row">
      <span>Tax Rate (%)</span>
      <InputNumber
        :modelValue="taxRate"
        @update:modelValue="$emit('update:taxRate', $event)"
        mode="decimal"
        :minFractionDigits="2"
        :maxFractionDigits="2"
        class="w-32"
        :inputStyle="{ height: '2rem' }"
      />
    </div>

    <div class="field-row">
      <span>Tax Amount</span>
      <InputNumber
        :modelValue="taxAmount"
        :readonly="true"
        mode="currency"
        currency="USD"
        class="w-32"
        :inputStyle="{ height: '2rem' }"
      />
    </div>

    <div class="field-row">
      <span>Additional Costs</span>
      <InputNumber
        :modelValue="additionalCosts"
        @update:modelValue="$emit('update:additionalCosts', $event)"
        mode="currency"
        currency="USD"
        class="w-32"
        :inputStyle="{ height: '2rem' }"
      />
    </div>

    <hr class="my-4">

    <div class="field-row total">
      <h3>Total</h3>
      <InputNumber
        :modelValue="totalCost"
        :readonly="true"
        mode="currency"
        currency="USD"
        class="w-32"
        :inputStyle="{ height: '2rem', fontWeight: 'bold' }"
      />
    </div>
  </div>
</template>

<style scoped>
.summary-content {
  padding: 0.5rem;
}

.field-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.field-row span {
  color: #666;
}

.total {
  margin-bottom: 0;
}

.total h3 {
  font-size: 1.125rem;
  font-weight: bold;
}

hr {
  border: none;
  border-top: 1px solid #e2e8f0;
}
</style>
