import "./index.scss"

wp.blocks.registerBlockType("plugin/featured-employee", {
  title: "Employee Callout",
  description: "Include a short description and link to an employee of your choice",
  icon: "businessman",
  category: "common",
  attributes: {
    emplName: {type: "string"}
  },
  edit: EditComponent,
  save: SaveComponent
})

function EditComponent(props) {
  return (
    <div className="featured-employee-wrapper">
      <div className="employee-select-container">
        <select onChange={e => props.setAttributes({emplName: e.target.value})}>
          <option value="">Select an employee</option>
          {queryEmployees.response.map(empl =>{
            return (
              <option value={empl.firstLastname} selected={props.attributes.emplName == empl.firstLastname}>
                {empl.firstLastname}
              </option>
            )
          })}
        </select>
      </div>
      <div class="py-3">
        The HTML preview of the selected plugin.
      </div>
    </div>
  )
}

function SaveComponent(props) {
  return (
    <>
    <div class="container">
      <div class="card text-center">
        <div class="card-body">
          {queryEmployees.response.map(empl =>{
              if(props.attributes.emplName == empl.firstLastname){
                return (
                  <>
                  <img src={empl.imageUrl} class="img-fluid mx-auto" width="300px"></img>
                  <h5 class="card-title py-2">{empl.firstLastname}</h5>
                  <p class="card-text py-2">{empl.position}</p>
                  </>
                )
              }
            })}
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            More information
          </button>
        </div>
      </div>
      
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            {queryEmployees.response.map(empl =>{
                if(props.attributes.emplName == empl.firstLastname){
                  return (
                    <>
                    <h5 class="card-title">{empl.firstLastname}</h5>
                    <p class="card-text">{empl.description}</p>
                    <ul>
                      {empl.socialNetwork.map(item=>{
                        return (
                          <>
                            <li><a href={item.link}>{item.link}</a></li>
                          </>
                        )
                      })}
                    </ul>
                    </>
                  )
                }
              })}
            </div>
          </div>
        </div>
      </div>
    </div>
    </>
  )
}