<script setup>
import { computed } from 'vue';
import Card from 'primevue/card';
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
  <Card class="sticky top-4">
    <template #title>Order Summary</template>
    <template #content>
      <div class="mb-3">
        <label for="subtotal" class="block mb-1 text-sm font-medium">Subtotal</label>
        <InputNumber
          id="subtotal"
          :modelValue="subtotal"
          :readonly="true"
          mode="currency"
          currency="USD"
          class="w-full"
        />
      </div>
      <div class="mb-3">
        <label for="taxRate" class="block mb-1 text-sm font-medium">Tax Rate (%)</label>
        <InputNumber
          id="taxRate"
          :modelValue="taxRate"
          @update:modelValue="$emit('update:taxRate', $event)"
          mode="decimal"
          :minFractionDigits="2"
          :maxFractionDigits="2"
          class="w-full"
        />
      </div>
      <div class="mb-3">
        <label for="taxAmount" class="block mb-1 text-sm font-medium">Tax Amount</label>
        <InputNumber
          id="taxAmount"
          :modelValue="taxAmount"
          :readonly="true"
          mode="currency"
          currency="USD"
          class="w-full"
        />
      </div>
      <div class="mb-3">
        <label for="additionalCosts" class="block mb-1 text-sm font-medium">Additional Costs</label>
        <InputNumber
          id="additionalCosts"
          :modelValue="additionalCosts"
          @update:modelValue="$emit('update:additionalCosts', $event)"
          mode="currency"
          currency="USD"
          class="w-full"
        />
      </div>
      <div class="mb-4">
        <label for="totalCost" class="block mb-1 text-sm font-medium">Total Cost</label>
        <InputNumber
          id="totalCost"
          :modelValue="totalCost"
          :readonly="true"
          mode="currency"
          currency="USD"
          class="w-full"
        />
      </div>
      <div class="flex justify-between">
        <Button label="Save Draft" @click="$emit('saveDraft')" class="p-button-secondary" />
        <Button label="Submit" @click="$emit('submit')" class="p-button-primary" />
      </div>
    </template>
  </Card>
</template>
