import { FormDataI, DbFormDataI } from './../components/static/types/FormDataI';
export const mapDBFields = (data: DbFormDataI): FormDataI => {
  let mapping = {
    title: data?.title,
    firstName: data?.first_name,
    surName: data?.last_name,
    email: data?.email,
    telephoneNumber: data?.phone,
    address: data?.address ? JSON.parse(data?.address) : { value: "" },
    signatureSrc: data?.signature_src,
    dateOfBirth: data?.date_of_birth,
    iAgreeTerms: (data?.agree_terms == 1) ? "true" : "false",
    taxPayer: data?.tax_payer,
    taxYear: data?.tax_year ? JSON.parse(data?.tax_year) : [],
    finalSubmission: false
  };

  return mapping;
}

