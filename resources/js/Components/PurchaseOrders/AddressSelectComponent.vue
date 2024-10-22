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
  <div class="addresses-container">
    <div v-for="(addresses, type) in formattedAddresses" :key="type" class="address-field">
      <label :for="`address-${type}`" class="field-label">
        {{ type.replace(/([A-Z])/g, ' $1').trim() }} Address
        <span v-if="settings.requireShippingAddress && type === 'shipTo'" class="required">*</span>
      </label>

      <div v-if="addresses.length === 0" class="no-addresses">
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
          <div class="address-option">
            {{ option.label }}
          </div>
        </template>
      </Select>
    </div>
  </div>
</template>

<style scoped>
.addresses-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.address-field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.field-label {
  font-weight: 500;
  color: #666;
}

.required {
  color: #ef4444;
  margin-left: 0.25rem;
}

.no-addresses {
  color: #666;
  font-style: italic;
  font-size: 0.875rem;
}

.address-option {
  font-size: 0.875rem;
  padding: 0.25rem 0;
}

:deep(.p-dropdown) {
  width: 100%;
}
</style>
