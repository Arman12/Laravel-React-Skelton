export interface ErrorsI {
    title: boolean;
    firstName: boolean;
    surName: boolean;
    email: boolean;
    telephoneNumber: boolean;
    address: boolean;
    signatureSrc: boolean;
    dateOfBirth: boolean;
    iAgreeTerms: boolean;
    taxYear: boolean;
}

export const defaultErrors: ErrorsI = {
    title: false,
    firstName: false,
    surName: false,
    email: false,
    telephoneNumber: false,
    address: false,
    signatureSrc: false,
    dateOfBirth: false,
    iAgreeTerms: false,
    taxYear: false
};