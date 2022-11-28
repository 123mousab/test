
export default {
    name: '{locale}',
    messages: {
        alpha:(field)=> `${field} must contain only characters`,
        alpha_num:(field)=> `${field} may contain only letters and numbers`,
        alpha_dash:(field)=> `${field} may contain letters or symbols - and _`,
        alpha_spaces:(field)=>  `${field} may contain only letters and spaces`,
        between:(field,[min,max])=>  `${field} must be between ${min} and ${max}`,
        confirmed:(field)=> `${field} does not match the original password`,
        digits:(field,[length])=>`${field} must contain only numbers and no more than ${length} digits`,
        dimensions:(field,[width,height])=> `${field} must be ${width} pixels in ${height} pixels`,
        email:(field)=> `${field} must be a valid email address`,
        excluded:(field)=> `Invalid field ${field}`,
        ext:(field)=> `Invalid ${field} file type`,
        image:(field)=> `${field} must be an image`,
        integer:(field)=> `The ${field} field must be an integer`,
        length:(field,[length])=> `The ${field} field should be ${length} digits`,
        max_value:(field,[min])=> `The value of ${field} must be less than or equal to ${min}`,
        max:(field,[length])=> `The ${field} field must contain at most ${length} characters`,
        mimes:(field)=> `Invalid ${field} file type`,
        min_value:(field,[min])=> `${field} must be greater than or equal to ${min}`,
        min:(field,[length])=> `The ${field} field must contain at least ${length} characters`,
        numeric:(field)=> `${field} can only contain numbers`,
        oneOf:(field)=> `The ${field} field must be a valid value`,
        regex:(field)=> `Invalid field ${field}`,
        required:(field)=> `Please enter ${field}`,
        required_if:(field)=> `${field} is required`,
        size:(field,[size])=> `${field} must be less than ${size} KB`,
        decimal:(field)=>`${field} can only contain numbers`,
    }
  };

