<script setup>
import { computed } from 'vue';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';

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

const emit = defineEmits(['update:taxRate', 'update:additionalCosts', 'saveDraft', 'submit']);

const taxAmount = computed(() => (props.subtotal * props.taxRate) / 100);
const totalCost = computed(() => props.subtotal + taxAmount.value + props.additionalCosts);
</script>

<template>
  <div class="p-3 order-summary">
    <h3 class="mb-3 text-lg font-bold">Order Summary</h3>
    <div class="grid grid-cols-2 gap-2">
      <div class="col-span-2">
        <label for="subtotal" class="block text-sm font-medium">Subtotal</label>
        <InputNumber
          id="subtotal"
          :modelValue="subtotal"
          :readonly="true"
          mode="currency"
          currency="USD"
          class="w-full"
          :inputStyle="{ height: '2rem' }"
        />
      </div>
      <div>
        <label for="taxRate" class="block text-sm font-medium">Tax Rate (%)</label>
        <InputNumber
          id="taxRate"
          :modelValue="taxRate"
          @update:modelValue="$emit('update:taxRate', $event)"
          mode="decimal"
          :minFractionDigits="2"
          :maxFractionDigits="2"
          class="w-full"
          :inputStyle="{ height: '2rem' }"
        />
      </div>
      <div>
        <label for="taxAmount" class="block text-sm font-medium">Tax Amount</label>
        <InputNumber
          id="taxAmount"
          :modelValue="taxAmount"
          :readonly="true"
          mode="currency"
          currency="USD"
          class="w-full"
          :inputStyle="{ height: '2rem' }"
        />
      </div>
      <div class="col-span-2">
        <label for="additionalCosts" class="block text-sm font-medium">Additional Costs</label>
        <InputNumber
          id="additionalCosts"
          :modelValue="additionalCosts"
          @update:modelValue="$emit('update:additionalCosts', $event)"
          mode="currency"
          currency="USD"
          class="w-full"
          :inputStyle="{ height: '2rem' }"
        />
      </div>
      <div class="col-span-2">
        <label for="totalCost" class="block text-sm font-medium">Total Cost</label>
        <InputNumber
          id="totalCost"
          :modelValue="totalCost"
          :readonly="true"
          mode="currency"
          currency="USD"
          class="w-full"
          :inputStyle="{ height: '2rem' }"
        />
      </div>
    </div>
    <div class="flex justify-between mt-4">
      <Button label="Save Draft" @click="$emit('saveDraft')" class="p-button-secondary p-button-sm" />
      <Button label="Submit" @click="$emit('submit')" class="p-button-primary p-button-sm" />
    </div>
  </div>
</template>

<style scoped>
.order-summary {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.grid {
  flex-grow: 1;
}
</style>
