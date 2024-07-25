import React, { ChangeEvent, FocusEvent, useState, MouseEvent } from 'react';
import { isValidPostCode, getFormattedAddressLines } from './../../../utils/addressUtils';
import { postHttpRequest } from "./../../../axios";
import './css/style.css';

import AddressLookupI from './types/AddressLookupI';
import PostDataI from './types/PostDataI';
import AddressI from './types/AddressI';
import ResponseDataI from './types/ResponseDataI';

import Modal from '../Modal';


/**
 * AddressLookup Component.
 * 
 * All rights Reseverd | Arhamsoft Pvt @2023
 * 
 * @param {Object} props - Component props.
 * @param {string} props.name - Name attribute for the input element.
 * @param {string} props.placeholder - Placeholder text for the input element.
 * @param {boolean} props.required - Indicates if the input is required.
 * @param {string} [props.buttonText] - Optional text for the button element.
 * @returns {JSX.Element} - JSX representation of the component.
 */

const AddressLookup: React.FC<AddressLookupI> = ({ name, id, value, placeholder, required, className, onChange, label, divClass, hasError, errorMessage, setHasError, buttonText, address, modalHeader, handleAddressUpdate }) => {

    const [loading, setLoading] = useState(false);
    const [isValid, setIsValid] = useState<boolean>(false);
    const [inputValue, setInputValue] = useState<string>('');
    const [modalOpen, setModalOpen] = useState<boolean>(false);
    const [selectedValue, setSelectedValue] = useState<string>('');
    const [selectedAddress, setSelectedAddress] = useState<AddressI | null>(null);
    const [blurred, setBlurred] = useState<number>(2);
    const [lookupData, setLookupData] = useState<ResponseDataI | null>({
        message: "",
        data: [],
        success: false
    });
    buttonText = loading ? 'Searching...' : buttonText;

    /**
     * Handles changes to the input value.
     *
     * @param {ChangeEvent<HTMLInputElement>} event - The change event.
     * @returns {void}
     */
    const handleInputChange = (event: ChangeEvent<HTMLInputElement>): void => {
        setBlurred(0);

        let value = event?.target?.value;
        if (onChange) {
            onChange(event);
            if (value && isValidPostCode(value)) {
                setHasError(false);
                return;
            }
            else if (required) {
                setHasError(true);
            }
        }

    };

    /**
     * Handles Blur to the input value.
     *
     * @param {ChangeEvent<HTMLInputElement>} event - The change event.
     * @returns {void}
     */
    const handleBlur = (event: FocusEvent<HTMLInputElement>) => {
        setBlurred(1);
        const value = event.target.value;
        if (value && isValidPostCode(value) && address?.postcode) {
            setIsValid(true);
            setHasError(false);
        }
        else if (required) {
            setIsValid(false);
            setHasError(true);

        }

    };

    /**
     * Handles changes to the input value.
     *
     * @param {ChangeEvent<HTMLSelectElement>} event - The change event.
     * @returns {void}
     */
    const handleSelectChange = (event: ChangeEvent<HTMLSelectElement>): void => {
        setSelectedValue(event?.target?.value);
        let raw = JSON.parse(event?.target?.value);
        handleAddressUpdate(raw?.Address.Lines);
        setSelectedAddress(raw);
        // closeModal();
    };

    /**
     * Handles Double Click to the select Element.
     *
     * @returns {void}
     */
     const handleSelectOnDoubleClick = (): void => {
        closeModal();
    };
    

    /**
     * Handles Submit to the input value.
     *
     * @returns {Promise<void>}
     */
    const search = async (): Promise<void> => {
        
        setBlurred(1);
        if (!value || !isValidPostCode(value)) {
            setHasError(true);
            setIsValid(false);
            return;
        }
        setLoading(true);
        setHasError(false);
        setIsValid(true);

        let response = await fetchAddress();
    
        if(response?.data && response?.data?.length < 2){
            let raw = response?.data as any;
            handleAddressUpdate(raw[0]?.Address.Lines);
            setSelectedAddress(raw[0]);
            setLoading(false);
            return;
        }
        setLookupData(response as ResponseDataI);
        openModal();
        setInputValue("");
        setLoading(false);
    };

    /**
     * Handles Submit to the input value.
     *
     * @returns {Promise}
     */
    const fetchAddress = async (): Promise<ResponseDataI> => {
        return new Promise(async (resolve, reject) => {
            try {
                const payload: PostDataI = {
                    postcode: `${value}`,
                };
                await postHttpRequest('api/addresses/get', payload)
                    .then(function (res) {
                        resolve(res.data);
                    });
            } catch (error) {
                reject(error);
            }
        });
    }

    /**
     * Handles for open Model.
     *
     * @returns {void}
    */
    const openModal = (): void => {
        setModalOpen(true);
    };


    /**
     * Handles for close Model.
     *
     * @returns {void}
    */
    const closeModal = (): void => {
        setModalOpen(false);
    };

    return (
        <>
            <div className={divClass}>
                {label && <label htmlFor={id}>{label} {required && <span>*</span>}</label>}
                <div className='postCode-Wrap'>
                    <input
                        type="text"
                        name={name}
                        id={id}
                        value={value}
                        required={required}
                        className={hasError ? 'has-error ' + className : isValid ? 'is-valid ' + className : className}
                        placeholder={placeholder ?? 'Enter postcode'}
                        onChange={handleInputChange}
                        onBlur={handleBlur}
                    />

                    <input
                        type="button"
                        value={buttonText ?? 'Find Address'}
                        className={`postcodeLookup btn ${loading ? 'loading' : ''} btn-primary`}
                        id="AddressCapture_FindButton"
                        onClick={search}
                        disabled={loading}
                    />
                </div>
                {(hasError && blurred > 0) && <p className='error'>{errorMessage}</p>}
            </div>
            <div className='allAddress-wrap'>
                {
                    address?.address1 &&
                    <input
                        type="text"
                        value={address?.address1}
                        name="address1"
                        readOnly
                        className='address-input'
                    />
                }
                {
                    address?.address2 &&
                    <input
                        type="text"
                        value={address?.address2}
                        name="address2"
                        readOnly
                        className='address-input'
                    />
                }
                {
                    address?.address3 &&
                    <input
                        type="text"
                        value={address?.address3}
                        name="address3"
                        readOnly
                        className='address-input'
                    />
                }
                {
                    address?.city &&
                    <input
                        type="text"
                        value={address?.city}
                        name="city"
                        readOnly
                        className='address-input'
                    />
                }
                {
                    address?.province &&
                    <input
                        type="text"
                        value={address?.province}
                        name="province"
                        readOnly
                        className='address-input'
                    />
                }
                {
                    address?.postcode &&
                    <input
                        type="text"
                        value={address?.postcode}
                        name="postcode"
                        readOnly
                        className='address-input'
                    />
                }
            </div>

            <Modal isOpen={modalOpen} onClose={closeModal} className="lookup-modal">
                <div className="modal-header">
                    <h2>{modalHeader ?? "Select Address"}</h2>
                </div>
                <div className="modal-body">
                    {
                        lookupData && lookupData.data ? (
                            <select size={10} value={selectedValue} onDoubleClick={() => handleSelectOnDoubleClick()} onChange={(e) => handleSelectChange(e)}>
                                {
                                    lookupData.data.map((address: AddressI, index: number) => {
                                        let completeAddress = address?.Address?.Lines
                                            ? getFormattedAddressLines(address?.Address?.Lines)
                                            : ''
                                        let jsonAddress = JSON.stringify(address);
                                        return <option key={index} value={jsonAddress}>
                                            {completeAddress}
                                        </option>
                                    })
                                }
                            </select>
                        ) : (
                            <p>{lookupData?.message ? lookupData?.message : "No data available"}.</p>
                        )
                    }
                </div>
            </Modal>
        </>
    )

};

export default AddressLookup;