import { ChangeEvent } from 'react';
interface AddressLookupI {
    name: string;
    id?:string;
    placeholder: string;
    className?: string;
    value?: string;
    label?: string;
    required: boolean,
    onChange?: (event: ChangeEvent<HTMLInputElement>) => void;
    divClass?: string;
    hasError?: boolean;
    errorMessage?: string;
    setHasError: (isValid: boolean) => void;
    handleAddressUpdate: (data: any) => void;
    buttonText?: string,
    modalHeader?: string,
    address: any,
}

export default AddressLookupI;