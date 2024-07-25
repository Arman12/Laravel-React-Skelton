/**
*
* @Author Muhammad Arman Saleem
* 
This functional component contains a multi-step form component with individual steps.
It facilitates user interaction to input data in a step-by-step manner.

*
* @Component <Step>
* 
* @Props stepNumber | totalStep | activeStep | setActiveStep | id | className | validStep
* 
* stepNumber: The current step number.
* totalStep: Total number of steps in the form.
* activeStep: Currently active step.
* setActiveStep: Function to update the active step.
* id: Identifier for the step component.
* className: CSS class for styling the step.
* validStep: Boolean indicating if the step is valid.

Content within each Step:
* Each step contains a title and input examples.
* The title showcases the purpose of the step.
* Examples include Title and Input components for data input.
* 
* -------------------------------------------------------------------
*
* @Component <Input>
* 
* @Props type | name | id | value | label | placeholder | required | onChange | divClass | hasError | setHasError | errorMessage
Input Component Example:
*
* type: Input field type (e.g., text).
* name: Identifier for the input.
* id: Unique ID for the input.
* value: Value of the input field.
* label: Label to display next to the input.
* placeholder: Placeholder text for the input.
* required: Boolean indicating if input is required.
* onChange: Function to handle input value change.
* divClass: CSS class for styling the input container.
* hasError: Boolean indicating if the input has an error.
* setHasError: Function to set error status for the input.
* errorMessage: Error message to display for invalid input.

Note:
- Each step may have its own input validation and handling logic.
*/


import React, { useState, ChangeEvent, useEffect } from 'react';
import { useNavigate, useSearchParams } from "react-router-dom";
import {
  Input,
  Title,
  Email,
  DOB,
  Step,
  Phone,
  AddressLookup,
  AgreeCheckBox,
  Radio,
  CheckBox,
  SignPad,
  ProgressB,
  Loader
} from '../common';
import Modal from '../common/Modal';
import { postHttpRequest, getHttpRequest } from "./../../axios";

import { scrollToError } from './../../utils/scrollToError';
import { deepEqual, arraysEqual, hasNonEmptyValues } from './../../utils/commonUtils';
import { mapDBFields } from './../../utils/formUtils';

import { FormDataI, defaultFormData } from './types/FormDataI';
import { ErrorsI, defaultErrors } from './types/ErrorsI';
import PointI from './types/PointI';


const Home: React.FC = () => {
  const [data, setData] = useState<FormDataI>(defaultFormData);
  let [sign, setSign] = useState<string>('');
  let [signOutPut, setSignOutPut] = useState<PointI[]>([]);// to maintain log to regenerate sign
  let [error, setError] = useState<ErrorsI>(defaultErrors);
  let [activeStep, setActiveStep] = useState<number>(1);
  let [validStep, setValidStep] = useState<[boolean]>([false]);
  let [id, setID] = useState<string>('');
  let [loader, setLoader] = useState<boolean>(true);
  const [lastChangeTime, setLastChangeTime] = useState<number | null>(null);
  const [searchParams, setSearchParams] = useSearchParams();
  const [modalOpen, setModalOpen] = useState<boolean>(false);
  const navigate = useNavigate();
  let totalStep = 5;
  /**
   * Required Fields According to Steps
   */
  // const requiredFields = [
  //   ['title', 'firstName', 'surName'],
  //   ['taxYear'],
  //   ['taxPayer'],
  //   ['email', 'dateOfBirth', 'telephoneNumber', 'address'],
  //   ['iAgreeTerms', 'signatureSrc']
  // ];
  const requiredFields = [
      [],
      [],
      [],
      [],
      []
    ];
  /**
   * Submit Lead Data to DB
   * @returned {void}
   */
  const submitData = () => {
    if ((data.telephoneNumber && !error.telephoneNumber) || (data.email && !error.email)) {
      let payload = { ...data, ['id']: id };
      postHttpRequest('api/leads/save', payload)
        .then(function (res) {
          setID(res.data);
          if (payload.finalSubmission) {
            setLoader(false);
            navigate('/thanks');
          }
        });
    }
  }

  /** If url have a lead ref as query string 
   * ?ref=xxxx
   * get that ref and fetch lead data from DB
   * **/
  useEffect(function () {
    let ref = searchParams.get("ref");
    if (ref) {
      setID(ref);
      getHttpRequest("api/leads/get/" + ref)
        .then(function (res) {
          if (res.data.phone) {
            let oldData = mapDBFields(res.data) as FormDataI;
            moveToStep(oldData);
          }
          setLoader(false);
        }
        ).catch(function (error) {
          setLoader(false);
        }
        )
    } else {
      setLoader(false);
    }
  }, []);

  /**** moveToStep
   * use requiredFields to check all steps valid fields
   * check if that fields are populated by DB or not
   * If Populated it moves automatically to incomplete step
   * *****/
  const moveToStep = (oldData: FormDataI) => {
    let activeStepNumber = 1;
    let valid: [boolean] = [false];
    requiredFields.forEach((value) => {
      let hasError = false;
      value.forEach(fieldName => {
        const fieldValue = data[fieldName as keyof FormDataI] as any;
        if (typeof fieldValue === 'object') {
          if (deepEqual(fieldValue, oldData[fieldName as keyof FormDataI])) {
            hasError = true;
          }
        } else if (Array.isArray(fieldValue)) {
          if (arraysEqual(fieldValue, oldData[fieldName as keyof FormDataI] as any)) {
            hasError = true;
          }
        } else {
          if (fieldValue === oldData[fieldName as keyof FormDataI]) {
            hasError = true;
          }
        }
      });
      valid[activeStepNumber] = !hasError;
      if (!hasError && activeStepNumber < totalStep) {
        activeStepNumber++;
      }
    });
    setValidStep(valid);
    setData(oldData);
    setActiveStep(activeStepNumber);
  }
  /**
   * Handles changes to the input value.
   *
   * 
   * @param {ChangeEvent<HTMLInputElement | HTMLSelectElement>} event - The change event.
   * @returns {void}
   */
  const handleInputChange = (event: ChangeEvent<HTMLInputElement | HTMLSelectElement>): void => {
    const { name, value } = event.target;
    setData({
      ...data,
      [name]: value,
    });
    /** handle overwrite move next here 
     * eg: Handle movement of steps without next button on the bases of user selection
     * ***/
    if (name == 'taxPayer') {
      if (value === 'yes') {
        setActiveStepValidate(activeStep + 1, false);
      } else if (value === 'no') {
        setModalOpen(true);
      }
    }
    setLastChangeTime(Date.now());
  };

  /**
   * Handles Address Update
   *
   * @param {<any>} address - The change event.
   * @returns {void}
   */
  const handleAddressUpdate = (address: any): void => {
    let name = 'address';
    setData({
      ...data,
      [name]: {
        value: address?.[6] || '',
        address1: address?.[0] || '',
        address2: address?.[1] || '',
        address3: address?.[2] || '',
        city: address?.[3] || '',
        province: address?.[4] || '',
        postcode: address?.[6] || '',
      },
    });
    setLastChangeTime(Date.now());
  };

  /**
   * Handles Address on Key Change
   *
   * @param {<HTMLInputElement | HTMLSelectElement>} address - The change event.
   * @returns {void}
   */
  const handleAddressChange = (event: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = event.target;
    setData({
      ...data,
      [name]: { ...data.address, value: value },
    });
    setLastChangeTime(Date.now());
  };

  /**
   * Handles Side Effects
   *
   * @returns {void}
   */
  useEffect(function () {
    setData({
      ...data,
      ['signatureSrc']: sign,
    });
    setLastChangeTime(Date.now());
  }, [sign]);
  /***** To active the next button of step after validating all inputs with in that step and to maintain log */
  useEffect(function () {
    let hasError = false;
    hasError = validation(false, requiredFields[activeStep - 1]);
    setValidStep({
      ...validStep,
      [activeStep]: !hasError,
    });

    const timeout = setTimeout(() => {
      // Calculate the time elapsed since the last change
      const currentTime = Date.now();
      const elapsedTime = currentTime - (lastChangeTime || 0);

      // If at least 2 seconds have passed since the last change, trigger the API call
      if (elapsedTime >= 2000) {
        submitData();
      }
    }, 2000); // Check every 2 seconds

    return () => clearTimeout(timeout);

  }, [data, lastChangeTime]);

  /**
   * Handle Custom Validations
   *
   * @param {boolean} - to handle show error messages or not
   * @returns {boolean}
   */
  const validation = (show: boolean, fields: string[]): boolean => {
    let hasError = false;
    const reqFields = fields; // Assuming requiredFields is defined
    if (reqFields?.length) {
      reqFields.forEach(fieldName => {
        const fieldValue = data[fieldName as keyof FormDataI] as any;
        const fieldError = error[fieldName as keyof ErrorsI];
        if (typeof fieldValue === 'object' && !Array.isArray(fieldValue) && !hasNonEmptyValues(fieldValue)) {
          if (show) {
            setError(prevError => ({ ...prevError, [fieldName]: true }));
          }
          hasError = true;
        } else {
          if (
            (Array.isArray(fieldValue) ? fieldValue.length === 0 : !fieldValue) ||
            fieldError
          ) {
            if (show) {
              setError(prevError => ({ ...prevError, [fieldName]: true }));
            }
            hasError = true;
          }
        }
      });
    }
    return hasError;
  }

  /**
   * Handle Custom Validations
   *
   * @param { number } - desired step
   * @param { boolean } - to active and disable validations
   * 
   * @returns { void }
   */
  const setActiveStepValidate = (no: number, validate: boolean): void => {
    let hasError = false;
    if (validate) {
      hasError = validation(true, requiredFields[activeStep - 1]);
    }
    if (!hasError) {
      if (no <= totalStep) {
        setActiveStep(no);
      } else {
        setLoader(true);
        setData({
          ...data,
          ['finalSubmission']: true,
        });
        setTimeout(function () { submitData(); }, 1000);
      }
    } else { scrollToError(); }
  };

  /**
   * Handle Checkbox for Multi Select
   *
   * @param { <HTMLInputElement> } - desired step
   * 
   * @returns { void }
   */
  const handleCheckboxMultiSelectChange = (event: React.ChangeEvent<HTMLInputElement>): void => {
    const { name, value, checked } = event.target;
    setData((prevData) => ({
      ...prevData,
      [name]: checked
        ? [...(prevData as any)[name], value]
        : (prevData as any)[name].filter((opt: string) => opt !== value),
    }));
    const fieldValue = data[name as keyof FormDataI] as any;
    if (event.target.checked) {
      setError({ ...error, [name]: false });
    } else if (!event.target.checked && fieldValue.length === 1) {
      setError({ ...error, [name]: true });
    } else if (!event.target.checked && fieldValue.length > 1) {
      setError({ ...error, [name]: false });
    }
  };

  return (
    <>
      {loader && <Loader />}

      <div className='formBox'>
        <div className='formBox-inner'>
          <ProgressB completed={activeStep - 1} maxCompleted={totalStep} />
          <Step stepNumber={1} totalStep={totalStep} activeStep={activeStep} setActiveStep={setActiveStepValidate} id="stepno1" className="form-steps" validStep={validStep}>
            <Title
              name="title"
              id="title"
              className="input-field"
              value={data.title}
              label="Title"
              placeholder='Select Title'
              required={true}
              onChange={handleInputChange}
              divClass="form-group"
              hasError={error.title}
              setHasError={(isValid: boolean) => setError({ ...error, ['title']: isValid })} errorMessage='Please select title' />
            <Input type="text" name="firstName" id="firstName" className="input-field" value={data.firstName} label="First Name" placeholder='Enter your first name' required={true} onChange={handleInputChange} divClass="form-group" hasError={error.firstName} setHasError={(isValid: boolean) => setError({ ...error, ['firstName']: isValid })} errorMessage='Please provide valid first name' />
            <Input type="text" name="surName" id="surName" className="input-field" value={data.surName} label="Sur Name" placeholder='Enter your sur name' required={true} onChange={handleInputChange} divClass="form-group" hasError={error.surName} setHasError={(isValid: boolean) => setError({ ...error, ['surName']: isValid })} errorMessage='Please provide valid sur name' />
          </Step>
          <Step stepNumber={2} totalStep={totalStep} activeStep={activeStep} setActiveStep={setActiveStepValidate} id="stepno2" className="form-steps " validStep={validStep}>
            <label>Please select tax years?</label>
            <div className='checkbox-wrap multicheckBox'>
              <CheckBox name='taxYear' id="taxYear1819" value='2018/19' onChange={handleCheckboxMultiSelectChange} checked={data.taxYear.includes('2018/19')}>2018/19</CheckBox>
              <CheckBox name='taxYear' id="taxYear1920" value='2019/20' onChange={handleCheckboxMultiSelectChange} checked={data.taxYear.includes('2019/20')}>2019/20</CheckBox>
              <CheckBox name='taxYear' id="taxYear2021" value='2020/21' onChange={handleCheckboxMultiSelectChange} checked={data.taxYear.includes('2020/21')}>2020/21</CheckBox>
              {error.taxYear && <p className='error'>Please select atleast one tax year</p>}
            </div>
          </Step>
          <Step stepNumber={3} totalStep={totalStep} activeStep={activeStep} setActiveStep={setActiveStepValidate} id="stepno3" className="form-steps" hideNav={true} validStep={validStep}>
            <label>Are you a tax payer?</label>
            <div className='checkbox-wrap '>
              <Radio name='taxPayer' id="taxPayerYes" value='yes' onChange={handleInputChange} checked={data.taxPayer === 'yes' || data.taxPayer === 'yess'}>Yes</Radio>
              <Radio name='taxPayer' id="taxPayerNo" value='no' onChange={handleInputChange} checked={data.taxPayer === 'no'}>No</Radio>
            </div>
          </Step>
          <Step stepNumber={4} totalStep={totalStep} activeStep={activeStep} setActiveStep={setActiveStepValidate} id="stepno4" className="form-steps" validStep={validStep}>

            <Phone name="telephoneNumber" id="telephoneNumber" className="input-field" value={data.telephoneNumber} label="Phone" placeholder="Enter your phone" required={true} onChange={handleInputChange} divClass="form-group" hasError={error.telephoneNumber} setHasError={(isValid: boolean) => setError({ ...error, ['telephoneNumber']: isValid })} errorMessage='Please provide valid phone' />

            <AddressLookup name="address" address={data?.address} id="address" className="input-field" value={data?.address?.value} label="Postcode" placeholder="Enter your postcode" required={true} onChange={handleAddressChange} handleAddressUpdate={handleAddressUpdate} divClass="form-group" hasError={error.address} setHasError={(isValid: boolean) => setError({ ...error, ['address']: isValid })} errorMessage='Please enter valid postcode and click find address' />

            <Email name="email" id="email" className="input-field" value={data.email} label="Email" placeholder='Enter your email' required={true} onChange={handleInputChange} divClass="form-group" hasError={error.email} setHasError={(isValid: boolean) => setError({ ...error, ['email']: isValid })} errorMessage='Please provide valid email' />

            <DOB name="dateOfBirth" id="dateOfBirth" className="input-field" value={data.dateOfBirth} label="Date of Birth" required={true} onChange={handleInputChange} divClass="form-group" hasError={error.dateOfBirth} setHasError={(isValid: boolean) => setError({ ...error, ['dateOfBirth']: isValid })} errorMessage='Please provide valid date of birth' dobFormat='YYYY-MM-DD' />

          </Step>
          <Step stepNumber={5} totalStep={totalStep} activeStep={activeStep} setActiveStep={setActiveStepValidate} id="stepno5" className="form-steps" validStep={validStep}>

            <SignPad padId="mysignpad1" setSign={setSign} setSignOutPut={setSignOutPut} signOutPut={signOutPut} sign={sign} hasError={error.signatureSrc} setHasError={(isValid: boolean) => setError({ ...error, ['signatureSrc']: isValid })} />

            <AgreeCheckBox name="iAgreeTerms" id="iAgreeTerms" value={data.iAgreeTerms} className="input-field" required={true} onChange={handleInputChange} divClass="form-group" hasError={error.iAgreeTerms} setHasError={(isValid: boolean) => setError({ ...error, ['iAgreeTerms']: isValid })} errorMessage='Please agree with terms'>
              I confirm i read <a href='/terms' target={"_blank"}>terms of business</a> and i want to proceed by request.
            </AgreeCheckBox>
          </Step>
        </div>
      </div>
      <Modal isOpen={modalOpen} onClose={() => { setModalOpen(false) }} className="information-modal">
        <div className="modal-header">
          <h2>Sorry you are not eligible!</h2>
        </div>
        <div className="modal-body">
          You must be a tax payer to submit a claim.
        </div>
      </Modal>
    </>
  )
};

export default Home;