<script setup>
import { computed } from 'vue';
import Select from 'primevue/select';

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
    default: () => ({
      billTo: null,
      shipFrom: null,
      shipTo: null,
      returnTo: null
    })
  },
  availableAddresses: {
    type: Object,
    required: true,
    default: () => ({
      billTo: [],
      shipFrom: [],
      shipTo: [],
      returnTo: []
    })
  },
  settings: {
    type: Object,
    required: false,
    default: () => ({
      requireShippingAddress: true
    })
  }
});

const emit = defineEmits(['update:modelValue']);

const formatAddress = (address) => {
  if (!address) return '';
  const parts = [
    address.street1,
    address.street2,
    address.city,
    address.state,
    address.postal_code,
    address.country
  ].filter(Boolean);
  return parts.join(', ');
};

const formattedAddresses = computed(() => {
  const result = {};
  for (const [type, addresses] of Object.entries(props.availableAddresses)) {
    result[type] = addresses.map(addr => ({
      label: formatAddress(addr),
      value: addr,
      ...addr
    }));
  }
  return result;
});

const updateAddress = (type, value) => {
  emit('update:modelValue', {
    ...props.modelValue,
    [type]: value
  });
};
</script>

<template>
  <div class="grid">
    <div v-for="(addresses, type) in formattedAddresses" :key="type" class="mb-4 col-12 md:col-6">
      <div class="h-full p-4 border rounded-lg">
        <div class="mb-2 text-lg font-medium capitalize">
          {{ type.replace(/([A-Z])/g, ' $1').trim() }} Address
          <span v-if="settings.requireShippingAddress && type === 'shipTo'" class="text-red-500">*</span>
        </div>

        <div v-if="addresses.length === 0" class="italic text-gray-500">
          No addresses available
        </div>

        <Select
          v-else
          :id="`address-${type}`"
          :modelValue="modelValue[type]"
          :options="addresses"
          optionLabel="label"
          :placeholder="`Select ${type.replace(/([A-Z])/g, ' $1').trim()} Address`"
          class="w-full"
          @update:modelValue="(value) => updateAddress(type, value)"
        >
          <template #option="{ option }">
            <div class="text-sm">
              {{ option.label }}
            </div>
          </template>
        </Select>
      </div>
    </div>
  </div>
</template>
