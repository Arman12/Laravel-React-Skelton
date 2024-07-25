import { ChangeEvent } from 'react';

interface PhoneI {
    name: string,
    id?: string;
    className?: string;
    value?: string;
    label?: string;
    placeholder?: string;
    required?: boolean;
    onChange?: (event: ChangeEvent<HTMLInputElement>) => void;
    divClass?: string;
    hasError?: boolean;
    errorMessage?: string;
    setHasError: (isValid: boolean) => void;
}



export default PhoneI;