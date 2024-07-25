import AddressI from './AddressI';
export interface FormDataI {
    title: string;
    firstName: string;
    surName: string;
    email: string;
    telephoneNumber: string;
    address: AddressI;
    signatureSrc: string;
    dateOfBirth: string;
    iAgreeTerms: string;
    taxPayer: string;
    taxYear: string[];
    finalSubmission: boolean;
}
export interface DbFormDataI {
    title: string;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    address: string;
    signature_src: string;
    date_of_birth: string;
    agree_terms: number;
    tax_payer: string;
    tax_year: string;
}
export const defaultFormData: FormDataI = {
    title: "",
    firstName: "",
    surName: "",
    email: "",
    telephoneNumber: "",
    address: { value: "" },
    signatureSrc: "",
    dateOfBirth: "",
    iAgreeTerms: "",
    taxPayer: "",
    taxYear: [],
    finalSubmission: false,
};
