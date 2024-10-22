<script setup>
import { computed } from 'vue';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';

const props = defineProps({
  poNumber: {
    type: String,
    required: true
  },
  date: {
    type: Date,
    required: true
  },
  status: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['update:poNumber', 'update:date', 'update:status']);

const formattedDate = computed({
  get: () => props.date,
  set: (value) => emit('update:date', value)
});
</script>

<template>
  <div class="p-4 mb-4 bg-surface-0 shadow-1">
    <div class="grid">
      <div class="col-12 md:col-4">
        <label for="poNumber" class="block mb-1 text-sm font-medium">PO Number</label>
        <InputText
          id="poNumber"
          :modelValue="poNumber"
          class="w-full"
          @update:modelValue="$emit('update:poNumber', $event)"
        />
      </div>
      <div class="col-12 md:col-4">
        <label for="date" class="block mb-1 text-sm font-medium">Date</label>
        <DatePicker
          id="date"
          v-model="formattedDate"
          dateFormat="dd/mm/yy"
          class="w-full"
        />
      </div>
      <div class="col-12 md:col-4">
        <label for="status" class="block mb-1 text-sm font-medium">Status</label>
        <InputText
          id="status"
          :modelValue="status"
          class="w-full"
          readonly
        />
      </div>
    </div>
  </div>
</template>
